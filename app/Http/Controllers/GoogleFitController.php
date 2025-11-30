<?php

namespace App\Http\Controllers;

use App\Models\GoogleFitToken;
use App\Models\HealthMetric;
use App\Services\GoogleFitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GoogleFitController extends Controller
{
    protected GoogleFitService $googleFitService;

    /**
     * Google Fit API Scopes needed
     */
    const SCOPES = [
        'https://www.googleapis.com/auth/fitness.heart_rate.read',
        'https://www.googleapis.com/auth/fitness.activity.read',
        'https://www.googleapis.com/auth/fitness.body.read',
    ];

    public function __construct(GoogleFitService $googleFitService)
    {
        $this->googleFitService = $googleFitService;
    }

    /**
     * Redirect to Google OAuth for Google Fit authorization
     */
    public function connect()
    {
        $clientId = config('services.google.client_id');
        $redirectUri = route('elderly.googlefit.callback');
        $scopes = implode(' ', self::SCOPES);

        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scopes,
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => csrf_token(),
        ]);

        return redirect("https://accounts.google.com/o/oauth2/v2/auth?{$params}");
    }

    /**
     * Handle OAuth callback from Google
     */
    public function callback(Request $request)
    {
        $user = Auth::user();

        // Check for errors
        if ($request->has('error')) {
            return redirect()->route('dashboard')->with('error', 'Failed to connect Google Fit: ' . $request->input('error'));
        }

        $code = $request->input('code');
        
        if (!$code) {
            return redirect()->route('dashboard')->with('error', 'No authorization code received');
        }

        try {
            // Exchange code for tokens
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => route('elderly.googlefit.callback'),
            ]);

            if (!$response->successful()) {
                return redirect()->route('dashboard')->with('error', 'Failed to get access token from Google');
            }

            $tokenData = $response->json();
            $tokenData['scopes'] = self::SCOPES;

            // Store tokens
            $this->googleFitService->storeTokens($user->id, $tokenData);

            return redirect()->route('dashboard')->with('success', '✅ Google Fit connected successfully! Your health data will now sync automatically.');

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error connecting Google Fit: ' . $e->getMessage());
        }
    }

    /**
     * Sync data from Google Fit
     */
    public function sync()
    {
        $user = Auth::user();
        $elderlyId = $user->profile?->id;

        if (!$elderlyId) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        $token = GoogleFitToken::where('user_id', $user->id)->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Google Fit not connected. Please connect first.'
            ], 400);
        }

        try {
            // Check if token expired and refresh if needed
            if ($token->isExpired()) {
                $token = $this->refreshAccessToken($token);
                if (!$token) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Google Fit session expired. Please reconnect.'
                    ], 401);
                }
            }

            $synced = [];

            // Fetch heart rate data
            $heartRate = $this->fetchHeartRate($token->access_token);
            if ($heartRate) {
                $metric = HealthMetric::updateOrCreate(
                    [
                        'elderly_id' => $elderlyId,
                        'type' => 'heart_rate',
                        'source' => 'google_fit',
                        'measured_at' => Carbon::today(),
                    ],
                    [
                        'value' => $heartRate,
                        'unit' => 'bpm',
                    ]
                );
                $synced['heart_rate'] = $heartRate;
            }

            // Fetch steps data
            $steps = $this->fetchSteps($token->access_token);
            if ($steps !== null) {
                $metric = HealthMetric::updateOrCreate(
                    [
                        'elderly_id' => $elderlyId,
                        'type' => 'steps',
                        'source' => 'google_fit',
                        'measured_at' => Carbon::today(),
                    ],
                    [
                        'value' => $steps,
                        'unit' => 'steps',
                    ]
                );
                $synced['steps'] = $steps;
            }

            return response()->json([
                'success' => true,
                'message' => 'Google Fit data synced successfully!',
                'synced' => $synced,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disconnect Google Fit
     */
    public function disconnect()
    {
        $user = Auth::user();

        GoogleFitToken::where('user_id', $user->id)->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Google Fit disconnected successfully'
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Google Fit disconnected');
    }

    /**
     * Check if Google Fit is connected
     */
    public function status()
    {
        $user = Auth::user();
        $token = GoogleFitToken::where('user_id', $user->id)->first();

        return response()->json([
            'connected' => $token !== null,
            'expires_at' => $token?->expires_at?->toISOString(),
            'is_expired' => $token?->isExpired() ?? true,
        ]);
    }

    /**
     * Refresh expired access token
     */
    private function refreshAccessToken(GoogleFitToken $token): ?GoogleFitToken
    {
        if (!$token->refresh_token) {
            return null;
        }

        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'refresh_token' => $token->refresh_token,
                'grant_type' => 'refresh_token',
            ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            
            $token->update([
                'access_token' => $data['access_token'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]);

            return $token->fresh();

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Fetch heart rate from Google Fit API
     */
    private function fetchHeartRate(string $accessToken): ?int
    {
        $now = Carbon::now();
        $startOfDay = Carbon::today();

        // Google Fit API uses nanoseconds
        $startTimeNanos = $startOfDay->timestamp * 1000000000;
        $endTimeNanos = $now->timestamp * 1000000000;

        try {
            $response = Http::withToken($accessToken)
                ->post('https://www.googleapis.com/fitness/v1/users/me/dataset:aggregate', [
                    'aggregateBy' => [
                        [
                            'dataTypeName' => 'com.google.heart_rate.bpm',
                        ]
                    ],
                    'bucketByTime' => [
                        'durationMillis' => 86400000 // 24 hours
                    ],
                    'startTimeMillis' => $startOfDay->timestamp * 1000,
                    'endTimeMillis' => $now->timestamp * 1000,
                ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            // Extract the average heart rate from response
            foreach ($data['bucket'] ?? [] as $bucket) {
                foreach ($bucket['dataset'] ?? [] as $dataset) {
                    foreach ($dataset['point'] ?? [] as $point) {
                        foreach ($point['value'] ?? [] as $value) {
                            if (isset($value['fpVal'])) {
                                return (int) round($value['fpVal']);
                            }
                        }
                    }
                }
            }

            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Fetch steps from Google Fit API
     */
    private function fetchSteps(string $accessToken): ?int
    {
        $now = Carbon::now();
        $startOfDay = Carbon::today();

        try {
            $response = Http::withToken($accessToken)
                ->post('https://www.googleapis.com/fitness/v1/users/me/dataset:aggregate', [
                    'aggregateBy' => [
                        [
                            'dataTypeName' => 'com.google.step_count.delta',
                        ]
                    ],
                    'bucketByTime' => [
                        'durationMillis' => 86400000 // 24 hours
                    ],
                    'startTimeMillis' => $startOfDay->timestamp * 1000,
                    'endTimeMillis' => $now->timestamp * 1000,
                ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            // Extract total steps from response
            $totalSteps = 0;
            foreach ($data['bucket'] ?? [] as $bucket) {
                foreach ($bucket['dataset'] ?? [] as $dataset) {
                    foreach ($dataset['point'] ?? [] as $point) {
                        foreach ($point['value'] ?? [] as $value) {
                            if (isset($value['intVal'])) {
                                $totalSteps += $value['intVal'];
                            }
                        }
                    }
                }
            }

            return $totalSteps > 0 ? $totalSteps : null;

        } catch (\Exception $e) {
            return null;
        }
    }
}
