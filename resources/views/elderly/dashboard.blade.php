<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SilverCare</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Montserrat', sans-serif; }
        
        /* Smooth Slider Styling */
        input[type=range] {
            -webkit-appearance: none;
            background: transparent;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 36px;
            width: 36px;
            border-radius: 50%;
            background: #fff;
            border: 6px solid currentColor;
            cursor: pointer;
            margin-top: -14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            transition: transform 0.1s ease;
        }
        input[type=range]:active::-webkit-slider-thumb {
            transform: scale(1.2);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 8px;
            background: #E5E7EB;
            border-radius: 999px;
        }
        input[type=range]:focus { outline: none; }
        
        /* Scrollbar hiding */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-[#F3F4F6] min-h-screen">

    <!-- NAV BAR -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-[#000080] rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/20">
                    <span class="text-white font-[900] text-lg">SC</span>
                </div>
                <h1 class="text-2xl font-[900] tracking-tight text-gray-900">SILVER<span class="text-[#000080]">CARE</span></h1>
            </div>
            
            <div class="flex items-center gap-6">
                <p class="hidden md:block text-sm font-bold text-gray-500 uppercase tracking-wider">
                    {{ now()->format('l, F j, Y') }}
                </p>
                <div class="h-8 w-[1px] bg-gray-200 hidden md:block"></div>
                <div class="flex items-center gap-3 pl-2">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-[#000080] font-[900] text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-bold text-gray-900 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 font-medium">Patient</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- DASHBOARD CONTENT -->
    <main class="max-w-[1600px] mx-auto px-6 lg:px-12 py-10">
        
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-3xl font-[800] text-gray-900">Dashboard Overview</h2>
            <p class="text-gray-500">Here's your daily health summary.</p>
        </div>

        <!-- THE DASHBOARD GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- LEFT COLUMN (3/12): Mood & Progress -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- 1. MOOD TRACKER (First - Most Important) -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
                    <h3 class="font-[800] text-lg text-gray-900 mb-1">Mood of the Day</h3>
                    <p class="text-xs text-gray-400 font-medium mb-6">How are you feeling right now?</p>

                    <div class="flex flex-col items-center">
                        <!-- Dynamic Emoji -->
                        <div id="moodEmoji" class="text-6xl mb-2 transition-transform duration-300">😐</div>
                        <p id="moodLabel" class="font-[800] text-lg mb-6 transition-colors duration-300 text-gray-600">Neutral</p>

                        <!-- Slider -->
                        <div class="w-full relative h-10 flex items-center">
                            <input 
                                type="range" 
                                id="moodSlider"
                                min="1" 
                                max="5" 
                                value="3"
                                class="w-full"
                                style="color: #6B7280;"
                            >
                        </div>

                        <!-- Auto-Save Indicator -->
                        <div class="h-6 mt-2 flex items-center justify-center">
                            <span id="moodSaved" class="text-xs font-bold text-green-600 opacity-0 transition-opacity duration-300">
                                ✓ Auto-saved
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 2. DAILY PROGRESS CARD -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100px] -mr-8 -mt-8 z-0"></div>
                    <div class="relative z-10">
                        <h3 class="font-[800] text-lg text-gray-900 mb-4">Daily Goals</h3>
                        
                        <div class="flex flex-col items-center py-4">
                            <!-- Circular Progress -->
                            <div class="relative w-32 h-32">
                                <svg class="transform -rotate-90 w-full h-full">
                                    <circle cx="64" cy="64" r="56" stroke="#F3F4F6" stroke-width="10" fill="none" />
                                    <circle 
                                        cx="64" cy="64" r="56" 
                                        stroke="#10B981" 
                                        stroke-width="10" 
                                        fill="none" 
                                        stroke-dasharray="352" 
                                        stroke-dashoffset="{{ 352 - (352 * $checklistProgress / 100) }}"
                                        stroke-linecap="round"
                                        class="transition-all duration-500"
                                    />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-[900] text-gray-900">{{ $checklistProgress }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Tasks Completed</span>
                                <span class="font-[800] text-sm text-gray-900">{{ $completedChecklists }}/{{ $totalChecklists }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Medications Today</span>
                                <span class="font-[800] text-sm text-gray-900">{{ $todayMedications->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CENTER COLUMN (6/12): Vitals Grid -->
            <div class="lg:col-span-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-[800] text-xl text-gray-900">Health Vitals</h3>
                    <span class="text-xs font-bold text-gray-400 bg-white px-3 py-1 rounded-full border border-gray-200">Updated: Today</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Vital Card: Blood Pressure (Placeholder - will connect to HealthMetric later) -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-red-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Blood Pressure</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                    <!-- Vital Card: Sugar Level -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-blue-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Sugar Level</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                    <!-- Vital Card: Temperature -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-orange-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Temperature</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                    <!-- Vital Card: Heart Rate -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-rose-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Heart Rate</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT COLUMN (3/12): Tasks & Meds -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- MEDICATION LIST (GREEN) -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-[24px] p-6 shadow-lg shadow-green-900/20 text-white h-[340px] flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-[800] text-lg">Medications</h3>
                        <a href="{{ route('elderly.medications') }}" class="text-xs font-bold hover:underline text-white/90">See All →</a>
                    </div>
                    
                    <div class="overflow-y-auto no-scrollbar space-y-3 flex-1">
                        @forelse($todayMedications as $medication)
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">
                                        💊
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-[800] text-white text-sm truncate">{{ $medication->name }}</h4>
                                        <p class="text-white/80 text-xs font-medium">{{ $medication->dosage }} {{ $medication->dosage_unit }}</p>
                                        @if(!empty($medication->times_of_day) && count($medication->times_of_day) > 0)
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @foreach(array_slice($medication->times_of_day, 0, 2) as $time)
                                                    <span class="bg-white/20 px-2 py-0.5 rounded text-[10px] font-bold">
                                                        {{ \Carbon\Carbon::parse($time)->format('g:i A') }}
                                                    </span>
                                                @endforeach
                                                @if(count($medication->times_of_day) > 2)
                                                    <span class="bg-white/20 px-2 py-0.5 rounded text-[10px] font-bold">
                                                        +{{ count($medication->times_of_day) - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-white/60 text-sm font-medium">No medications for today</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- CHECKLIST WIDGET -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-[800] text-lg text-gray-900">Today's Tasks</h3>
                        <a href="{{ route('elderly.checklists') }}" class="text-xs font-bold text-[#000080] hover:underline">See All →</a>
                    </div>
                    <div class="space-y-3" id="checklistContainer">
                        @forelse($todayChecklists->take(4) as $checklist)
                            <div class="checklist-item flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors" data-id="{{ $checklist->id }}">
                                <button 
                                    onclick="toggleChecklist({{ $checklist->id }})"
                                    class="flex-shrink-0 w-6 h-6 rounded-md border-2 {{ $checklist->is_completed ? 'bg-green-500 border-green-500' : 'bg-white border-gray-300 hover:border-green-500' }} flex items-center justify-center transition-all mt-0.5">
                                    @if($checklist->is_completed)
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </button>
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-bold text-gray-900 {{ $checklist->is_completed ? 'line-through text-gray-400' : '' }} truncate">
                                        {{ $checklist->title }}
                                    </p>
                                    @if($checklist->due_time)
                                        <p class="text-xs text-gray-500 font-medium">
                                            {{ \Carbon\Carbon::parse($checklist->due_time)->format('g:i A') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-400 text-sm font-medium">All tasks completed! 🎉</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Mood Tracker with Auto-Save
        const moodSlider = document.getElementById('moodSlider');
        const moodEmoji = document.getElementById('moodEmoji');
        const moodLabel = document.getElementById('moodLabel');
        const moodSaved = document.getElementById('moodSaved');

        const moods = [
            { emoji: '😢', label: 'Very Sad', color: '#EF4444' },
            { emoji: '☹️', label: 'Sad', color: '#F97316' },
            { emoji: '😐', label: 'Neutral', color: '#6B7280' },
            { emoji: '🙂', label: 'Happy', color: '#65A30D' },
            { emoji: '😄', label: 'Very Happy', color: '#16A34A' }
        ];

        let saveTimeout;

        moodSlider.addEventListener('input', function() {
            const value = parseInt(this.value);
            const mood = moods[value - 1];

            // Update UI
            moodEmoji.style.transform = 'scale(0.8)';
            setTimeout(() => {
                moodEmoji.textContent = mood.emoji;
                moodLabel.textContent = mood.label;
                moodEmoji.style.transform = 'scale(1)';
                moodSlider.style.color = mood.color;
            }, 100);

            // Auto-save after 1 second
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => saveMood(value), 1000);
        });

        function saveMood(value) {
            // Show saved indicator
            moodSaved.style.opacity = '1';
            setTimeout(() => {
                moodSaved.style.opacity = '0';
            }, 2000);

            // TODO: Send to backend
            console.log('Mood saved:', value);
        }

        // Checklist Toggle with AJAX
        function toggleChecklist(checklistId) {
            const item = document.querySelector(`.checklist-item[data-id="${checklistId}"]`);
            const button = item.querySelector('button');
            const text = item.querySelector('p');

            fetch(`/my-checklists/${checklistId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                // Toggle UI
                if (data.is_completed) {
                    button.classList.remove('bg-white', 'border-gray-300', 'hover:border-green-500');
                    button.classList.add('bg-green-500', 'border-green-500');
                    button.innerHTML = '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
                    text.classList.add('line-through', 'text-gray-400');
                } else {
                    button.classList.remove('bg-green-500', 'border-green-500');
                    button.classList.add('bg-white', 'border-gray-300', 'hover:border-green-500');
                    button.innerHTML = '';
                    text.classList.remove('line-through', 'text-gray-400');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

</body>
</html>