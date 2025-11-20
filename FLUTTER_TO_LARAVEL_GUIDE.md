# Flutter to Laravel Conversion Guide

## Service Layer Mapping

Your Flutter `lib/services/` maps to Laravel as follows:

### ✅ Kept & Adapted

| Flutter Service | Laravel Equivalent | Notes |
|----------------|-------------------|-------|
| `medication_service.dart` | `app/Services/MedicationService.php` | CRUD + scheduling logic |
| `user_service.dart` | `app/Services/UserService.php` | Profile management |
| `checklist_service.dart` | `app/Services/ChecklistService.php` | Task management |
| `calendar_service.dart` | `app/Services/CalendarService.php` | Event management |
| `google_fit_service.dart` | `app/Services/GoogleFitService.php` | OAuth + API calls |
| `heart_rate_service.dart` | `app/Services/HealthMetricsService.php` | Merge all vitals |
| `blood_pressure_service.dart` | (merge into HealthMetricsService) | |
| `temperature_service.dart` | (merge into HealthMetricsService) | |
| `sugar_level_service.dart` | (merge into HealthMetricsService) | |
| `mood_service.dart` | `app/Services/MoodService.php` | Optional feature |

### ❌ Removed (Mobile-Only)

| Flutter Service | Reason |
|----------------|--------|
| `notification_service.dart` | Local push notifications - use Laravel Mail instead |
| `push_notification_service.dart` | FCM - use email notifications |
| `persistent_notification_service.dart` | Converted to notification history table |
| `calendar_notification_service.dart` | Use Laravel's scheduled tasks + email |
| `sos_listener_service.dart` | Removed feature |
| `sos_service.dart` | Removed feature |

## Model Conversion

### User Models

**Flutter:**
```dart
// user_model.dart
class UserModel {
  final String id;
  final String email;
  final String fullName;
  final String userType; // "elderly" | "caregiver"
}

// elderly_model.dart  
class ElderlyModel {
  final String userId;
  final String username;
  final String phoneNumber;
  final EmergencyContact? emergencyContact;
  final MedicalInfo? medicalInfo;
}

// caregiver_model.dart
class CaregiverModel {
  final String userId;
  final String email;
  final String relationship;
}
```

**Laravel:**
```php
// app/Models/User.php (built-in)
class User extends Authenticatable {
    protected $fillable = ['name', 'email', 'password'];
}

// app/Models/UserProfile.php
class UserProfile extends Model {
    protected $fillable = [
        'user_id', 'user_type', 'username', 'phone_number',
        'sex', 'age', 'weight', 'height', 'emergency_contact',
        'medical_info', 'relationship', 'profile_completed'
    ];
    
    protected $casts = [
        'emergency_contact' => 'array',
        'medical_info' => 'array',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function isElderly() {
        return $this->user_type === 'elderly';
    }
    
    public function isCaregiver() {
        return $this->user_type === 'caregiver';
    }
}
```

### Medication Model

**Flutter:**
```dart
class MedicationModel {
  final String elderlyId;
  final String caregiverId;
  final String name;
  final String dosage;
  final List<String> daysOfWeek;
  final List<String> timesOfDay;
  final DateTime startDate;
  final DateTime? endDate;
}
```

**Laravel:**
```php
// app/Models/Medication.php
class Medication extends Model {
    protected $fillable = [
        'elderly_id', 'caregiver_id', 'name', 'dosage',
        'instructions', 'days_of_week', 'specific_dates',
        'times_of_day', 'start_date', 'end_date', 'is_active'
    ];
    
    protected $casts = [
        'days_of_week' => 'array',
        'specific_dates' => 'array',
        'times_of_day' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    
    public function elderly() {
        return $this->belongsTo(UserProfile::class, 'elderly_id');
    }
    
    public function caregiver() {
        return $this->belongsTo(UserProfile::class, 'caregiver_id');
    }
    
    public function logs() {
        return $this->hasMany(MedicationLog::class);
    }
}
```

### Health Data Model

**Flutter:**
```dart
class HealthDataModel {
  final String elderlyId;
  final String type; // "heart_rate" | "blood_pressure" etc.
  final double value;
  final DateTime measuredAt;
  final String source; // "manual" | "google_fit"
}
```

**Laravel:**
```php
// app/Models/HealthMetric.php
class HealthMetric extends Model {
    protected $fillable = [
        'elderly_id', 'type', 'value', 'unit',
        'measured_at', 'source'
    ];
    
    protected $casts = [
        'measured_at' => 'datetime',
    ];
    
    public function elderly() {
        return $this->belongsTo(UserProfile::class, 'elderly_id');
    }
    
    // Scopes for filtering
    public function scopeHeartRate($query) {
        return $query->where('type', 'heart_rate');
    }
    
    public function scopeBloodPressure($query) {
        return $query->where('type', 'blood_pressure');
    }
}
```

## Service Layer Example

### Flutter Service

```dart
// medication_service.dart
class MedicationService {
  final FirebaseFirestore _firestore = FirebaseFirestore.instance;
  
  Future<void> addMedication(MedicationModel medication) async {
    await _firestore.collection('medications').add(medication.toMap());
  }
  
  Stream<List<MedicationModel>> getMedicationsForElderly(String elderlyId) {
    return _firestore
        .collection('medications')
        .where('elderlyId', isEqualTo: elderlyId)
        .snapshots()
        .map((snapshot) => snapshot.docs
            .map((doc) => MedicationModel.fromDoc(doc))
            .toList());
  }
}
```

### Laravel Service

```php
// app/Services/MedicationService.php
namespace App\Services;

use App\Models\Medication;
use App\Models\MedicationLog;
use Carbon\Carbon;

class MedicationService {
    
    public function createMedication(array $data) {
        $medication = Medication::create($data);
        
        // Schedule initial logs
        $this->scheduleLogsForMedication($medication);
        
        return $medication;
    }
    
    public function getMedicationsForElderly($elderlyId) {
        return Medication::where('elderly_id', $elderlyId)
            ->where('is_active', true)
            ->with(['caregiver', 'logs'])
            ->get();
    }
    
    public function markAsTaken($logId) {
        $log = MedicationLog::findOrFail($logId);
        $log->update([
            'is_taken' => true,
            'taken_at' => now(),
        ]);
        
        // Broadcast event to caregiver dashboard
        broadcast(new \App\Events\MedicationTaken($log));
        
        return $log;
    }
    
    protected function scheduleLogsForMedication(Medication $medication) {
        $startDate = Carbon::parse($medication->start_date);
        $endDate = $medication->end_date 
            ? Carbon::parse($medication->end_date) 
            : $startDate->copy()->addMonths(3);
        
        while ($startDate->lte($endDate)) {
            $dayName = $startDate->format('l'); // Monday, Tuesday, etc.
            
            if (in_array($dayName, $medication->days_of_week)) {
                foreach ($medication->times_of_day as $time) {
                    MedicationLog::create([
                        'medication_id' => $medication->id,
                        'elderly_id' => $medication->elderly_id,
                        'scheduled_time' => $startDate->copy()->setTimeFromTimeString($time),
                        'is_taken' => false,
                    ]);
                }
            }
            
            $startDate->addDay();
        }
    }
}
```

## Controller Usage

```php
// app/Http/Controllers/MedicationController.php
namespace App\Http\Controllers;

use App\Services\MedicationService;
use Illuminate\Http\Request;

class MedicationController extends Controller {
    
    protected $medicationService;
    
    public function __construct(MedicationService $medicationService) {
        $this->medicationService = $medicationService;
    }
    
    public function index() {
        $elderlyId = auth()->user()->profile->id;
        $medications = $this->medicationService->getMedicationsForElderly($elderlyId);
        
        return view('medications.index', compact('medications'));
    }
    
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'dosage' => 'required|string',
            'days_of_week' => 'required|array',
            'times_of_day' => 'required|array',
            'start_date' => 'required|date',
        ]);
        
        $medication = $this->medicationService->createMedication($validated);
        
        return redirect()->route('medications.index')
            ->with('success', 'Medication added successfully');
    }
}
```

## Real-Time Updates (Firestore → Reverb)

### Flutter (Firestore)

```dart
StreamBuilder<List<MedicationLog>>(
  stream: medicationService.getLogsStream(elderlyId),
  builder: (context, snapshot) {
    if (snapshot.hasData) {
      return ListView.builder(
        itemCount: snapshot.data!.length,
        itemBuilder: (context, index) {
          return MedicationLogCard(log: snapshot.data![index]);
        },
      );
    }
    return CircularProgressIndicator();
  },
)
```

### Laravel (Reverb + Blade)

**Backend:**
```php
// app/Events/MedicationTaken.php
class MedicationTaken implements ShouldBroadcast {
    public $medicationLog;
    
    public function broadcastOn() {
        return new PrivateChannel('caregiver.' . $this->medicationLog->medication->caregiver_id);
    }
}
```

**Frontend:**
```html
<!-- resources/views/caregiver/dashboard.blade.php -->
@push('scripts')
<script type="module">
    import Echo from 'laravel-echo';
    
    window.Echo.private('caregiver.{{ auth()->user()->profile->id }}')
        .listen('MedicationTaken', (e) => {
            // Update UI
            const logElement = document.getElementById(`log-${e.medicationLog.id}`);
            logElement.classList.add('taken');
            logElement.querySelector('.status').textContent = 'Taken';
            
            // Show toast notification
            showToast('Medication taken by ' + e.medicationLog.elderly.username);
        });
</script>
@endpush
```

## Google Fit Integration

### Flutter

```dart
// google_fit_service.dart
class GoogleFitService {
  Future<void> authorize() async {
    final account = await googleSignIn.signIn();
    final auth = await account!.authentication;
    // Store tokens
  }
  
  Future<List<HealthPoint>> fetchHeartRate() async {
    final response = await http.post(
      'https://www.googleapis.com/fitness/v1/users/me/dataset:aggregate',
      headers: {'Authorization': 'Bearer $accessToken'},
      body: json.encode({...}),
    );
    return parseResponse(response);
  }
}
```

### Laravel

```php
// app/Services/GoogleFitService.php
namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

class GoogleFitService {
    
    public function getAuthUrl() {
        return Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/fitness.heart_rate.read',
                'https://www.googleapis.com/auth/fitness.activity.read',
            ])
            ->redirect()->getTargetUrl();
    }
    
    public function handleCallback() {
        $user = Socialite::driver('google')->user();
        
        // Store tokens
        auth()->user()->googleFitToken()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'access_token' => encrypt($user->token),
                'refresh_token' => encrypt($user->refreshToken),
                'expires_at' => now()->addSeconds($user->expiresIn),
            ]
        );
    }
    
    public function fetchHeartRateData($userId) {
        $token = auth()->user()->googleFitToken;
        
        if ($token->expires_at < now()) {
            $this->refreshToken($token);
        }
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . decrypt($token->access_token),
        ])->post('https://www.googleapis.com/fitness/v1/users/me/dataset:aggregate', [
            'aggregateBy' => [[
                'dataTypeName' => 'com.google.heart_rate.bpm',
            ]],
            'bucketByTime' => ['durationMillis' => 86400000], // 1 day
            'startTimeMillis' => now()->subDays(7)->timestamp * 1000,
            'endTimeMillis' => now()->timestamp * 1000,
        ]);
        
        return $this->parseAndStoreData($response->json(), $userId);
    }
}
```

## Key Takeaways

1. **Firestore Collections → PostgreSQL Tables**
   - Use migrations for schema
   - Eloquent ORM for queries

2. **Streams → Broadcasting**
   - Use Laravel Reverb for real-time
   - Echo on frontend for listening

3. **Services Pattern Stays**
   - Keep business logic in Services
   - Controllers stay thin

4. **JSON Fields for Complex Data**
   - emergency_contact, medical_info stored as JSON
   - Laravel casts handle serialization

5. **Relationships are Easier**
   - Eloquent handles joins automatically
   - `with()` for eager loading

6. **Testing**
   - PHPUnit instead of Flutter tests
   - Database factories for seeding
