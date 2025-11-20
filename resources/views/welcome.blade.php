<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SilverCare - Elderly Care Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center">
            <!-- Logo -->
            <div class="mb-8 flex justify-center">
                <div class="w-32 h-32 bg-white rounded-2xl shadow-lg flex items-center justify-center">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600" />
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-5xl font-extrabold text-gray-900 tracking-tight mb-4 drop-shadow-md" style="font-family: 'Montserrat', sans-serif;">
                SILVERCARE
            </h1>
            
            <p class="text-lg text-gray-600 mb-12">
                Comprehensive care management for elderly loved ones
            </p>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="{{ route('register') }}" 
                   class="block w-full py-4 px-6 bg-white text-gray-900 font-bold text-lg rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-105"
                   style="font-family: 'Montserrat', sans-serif;">
                    SIGN UP
                </a>

                <a href="{{ route('login') }}" 
                   class="block w-full py-4 px-6 bg-white text-gray-900 font-bold text-lg rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-105"
                   style="font-family: 'Montserrat', sans-serif;">
                    SIGN IN
                </a>
            </div>

            <!-- Footer Info -->
            <div class="mt-12 text-sm text-gray-500">
                <p>Track medications • Monitor health • Stay connected</p>
            </div>
        </div>
    </div>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
</body>
</html>
