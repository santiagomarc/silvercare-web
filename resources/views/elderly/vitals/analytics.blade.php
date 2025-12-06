<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Health Vitals Analytics - SilverCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .period-btn.active {
            @apply bg-white text-gray-900 shadow-sm;
        }
        .period-btn:not(.active) {
            @apply text-gray-600 hover:text-gray-900;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">

<div class="min-h-screen pb-20">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-[900] text-gray-900">Health Analytics</h1>
                        <p class="text-sm text-gray-500 font-[600]">Track your vitals over time</p>
                    </div>
                </div>
                
                <!-- Time Period Selector -->
                <div class="flex bg-gray-100 rounded-xl p-1">
                    <button onclick="changePeriod('7days')" class="period-btn active px-4 py-2 rounded-lg text-sm font-[700] transition-all" data-period="7days">7 Days</button>
                    <button onclick="changePeriod('30days')" class="period-btn px-4 py-2 rounded-lg text-sm font-[700] transition-all" data-period="30days">30 Days</button>
                    <button onclick="changePeriod('90days')" class="period-btn px-4 py-2 rounded-lg text-sm font-[700] transition-all" data-period="90days">90 Days</button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Readings -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-[700] text-gray-500 uppercase">Total Readings</h3>
                </div>
                <p class="text-3xl font-[900] text-gray-900">{{ $totalReadings }}</p>
                <p class="text-xs text-gray-400 font-[600] mt-1">Last 30 days</p>
            </div>

            <!-- This Week -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-[700] text-gray-500 uppercase">This Week</h3>
                </div>
                <p class="text-3xl font-[900] text-gray-900">{{ $readingsThisWeek }}</p>
                <p class="text-xs text-gray-400 font-[600] mt-1">Last 7 days</p>
            </div>

            <!-- Consistency Score -->
            @php
                $consistencyScore = $totalReadings > 0 ? min(100, round(($readingsThisWeek / 28) * 100)) : 0;
            @endphp
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-[700] text-gray-500 uppercase">Consistency</h3>
                </div>
                <p class="text-3xl font-[900] text-gray-900">{{ $consistencyScore }}%</p>
                <p class="text-xs text-gray-400 font-[600] mt-1">Tracking score</p>
            </div>

            <!-- Average Daily -->
            @php
                $avgDaily = $totalReadings > 0 ? round($totalReadings / 30, 1) : 0;
            @endphp
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-[700] text-gray-500 uppercase">Daily Average</h3>
                </div>
                <p class="text-3xl font-[900] text-gray-900">{{ $avgDaily }}</p>
                <p class="text-xs text-gray-400 font-[600] mt-1">Readings per day</p>
            </div>
        </div>

        <!-- Vitals Analytics Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            @foreach($analyticsData as $type => $data)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-{{ $data['config']['color'] }}-50 to-{{ $data['config']['color'] }}-100 p-6 border-b border-{{ $data['config']['color'] }}-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-2xl">
                                {{ $data['config']['icon'] }}
                            </div>
                            <div>
                                <h3 class="text-lg font-[900] text-gray-900">{{ $data['config']['name'] }}</h3>
                                <p class="text-sm font-[600] text-gray-600">{{ $data['config']['unit'] }}</p>
                            </div>
                        </div>
                        <a href="{{ route('elderly.vitals.' . $type) }}" class="px-4 py-2 bg-white rounded-lg text-sm font-[700] text-{{ $data['config']['color'] }}-600 hover:bg-{{ $data['config']['color'] }}-50 transition-colors">
                            View Details
                        </a>
                    </div>
                </div>

                <!-- Stats by Period -->
                <div class="p-6">
                    @foreach(['7days', '30days', '90days'] as $period)
                    <div class="period-data {{ $period !== '7days' ? 'hidden' : '' }}" data-period="{{ $period }}">
                        @if(($data[$period]['count'] ?? 0) > 0)
                            <!-- Chart Canvas -->
                            <div class="mb-6 bg-gray-50 rounded-xl p-4">
                                <canvas id="chart-{{ $type }}-{{ $period }}" height="200"></canvas>
                            </div>

                            <!-- Statistics -->
                            <div class="grid grid-cols-3 gap-4">
                                @if($type === 'blood_pressure')
                                    <div class="text-center">
                                        <p class="text-xs font-[700] text-gray-500 uppercase mb-1">Avg Systolic</p>
                                        <p class="text-2xl font-[900] text-gray-900">{{ $data[$period]['systolic_avg'] ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $data[$period]['systolic_min'] ?? '' }}-{{ $data[$period]['systolic_max'] ?? '' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-[700] text-gray-500 uppercase mb-1">Avg Diastolic</p>
                                        <p class="text-2xl font-[900] text-gray-900">{{ $data[$period]['diastolic_avg'] ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $data[$period]['diastolic_min'] ?? '' }}-{{ $data[$period]['diastolic_max'] ?? '' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-[700] text-gray-500 uppercase mb-1">Readings</p>
                                        <p class="text-2xl font-[900] text-gray-900">{{ $data[$period]['count'] }}</p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <p class="text-xs font-[700] text-gray-500 uppercase mb-1">Average</p>
                                        <p class="text-2xl font-[900] text-gray-900">{{ $data[$period]['avg'] ?? 'N/A' }}</p>
                                        <p class="text-xs text-{{ $data['config']['color'] }}-600 font-[600] mt-1">{{ $data['config']['unit'] }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-[700] text-gray-500 uppercase mb-1">Range</p>
                                        <p class="text-2xl font-[900] text-gray-900">{{ $data[$period]['min'] ?? 'N/A' }}-{{ $data[$period]['max'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-[700] text-gray-500 uppercase mb-1">Trend</p>
                                        @php
                                            $trend = $data[$period]['trend'] ?? 'stable';
                                            $trendIcon = $trend === 'increasing' ? '↗' : ($trend === 'decreasing' ? '↘' : '→');
                                            $trendColor = $trend === 'stable' ? 'text-gray-600' : ($trend === 'increasing' ? 'text-red-600' : 'text-green-600');
                                        @endphp
                                        <p class="text-2xl font-[900] {{ $trendColor }}">{{ $trendIcon }}</p>
                                        <p class="text-xs text-gray-400 mt-1 capitalize">{{ $trend }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Recent Activity -->
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <h4 class="text-sm font-[700] text-gray-500 uppercase mb-3">Recent Activity</h4>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    @foreach($data[$period]['metrics']->take(5) as $metric)
                                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                                        <span class="text-sm font-[700] text-gray-900">
                                            @if($type === 'blood_pressure')
                                                {{ $metric->value_text }}
                                            @else
                                                {{ number_format($metric->value, 1) }} {{ $data['config']['unit'] }}
                                            @endif
                                        </span>
                                        <span class="text-xs font-[600] text-gray-500">{{ $metric->measured_at->format('M d, g:i A') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- No Data State -->
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-[800] text-gray-900 mb-2">No Data Available</h4>
                                <p class="text-sm text-gray-500 font-[600] mb-4">Start tracking your {{ strtolower($data['config']['name']) }} to see analytics</p>
                                <a href="{{ route('elderly.vitals.' . $type) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-{{ $data['config']['color'] }}-500 text-white rounded-xl font-[700] hover:bg-{{ $data['config']['color'] }}-600 transition-colors">
                                    <span>+</span>
                                    Add Reading
                                </a>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

<script>
    const charts = {};
    const analyticsData = @json($analyticsData);

    // Initialize all charts
    function initCharts() {
        Object.keys(analyticsData).forEach(type => {
            const data = analyticsData[type];
            ['7days', '30days', '90days'].forEach(period => {
                const periodData = data[period];
                if (periodData.count > 0) {
                    const canvasId = `chart-${type}-${period}`;
                    const ctx = document.getElementById(canvasId);
                    if (ctx) {
                        charts[canvasId] = createChart(ctx, type, periodData, data.config);
                    }
                }
            });
        });
    }

    function createChart(ctx, type, periodData, config) {
        const metrics = periodData.metrics;
        const labels = metrics.map(m => new Date(m.measured_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        
        const colorMap = {
            'red': { bg: 'rgba(239, 68, 68, 0.1)', border: 'rgb(239, 68, 68)' },
            'blue': { bg: 'rgba(59, 130, 246, 0.1)', border: 'rgb(59, 130, 246)' },
            'orange': { bg: 'rgba(249, 115, 22, 0.1)', border: 'rgb(249, 115, 22)' },
            'rose': { bg: 'rgba(244, 63, 94, 0.1)', border: 'rgb(244, 63, 94)' },
        };
        const colors = colorMap[config.color] || colorMap.blue;

        let datasets = [];

        if (type === 'blood_pressure') {
            const systolic = [];
            const diastolic = [];
            metrics.forEach(m => {
                if (m.value_text) {
                    const parts = m.value_text.split('/');
                    systolic.push(parseInt(parts[0]));
                    diastolic.push(parseInt(parts[1]));
                }
            });
            
            datasets = [
                {
                    label: 'Systolic',
                    data: systolic,
                    borderColor: colors.border,
                    backgroundColor: colors.bg,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Diastolic',
                    data: diastolic,
                    borderColor: 'rgba(147, 51, 234, 1)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                }
            ];
        } else {
            const values = metrics.map(m => parseFloat(m.value));
            datasets = [{
                label: config.name,
                data: values,
                borderColor: colors.border,
                backgroundColor: colors.bg,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
            }];
        }

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: type === 'blood_pressure',
                        position: 'top',
                        labels: {
                            font: { weight: 'bold', size: 11 },
                            usePointStyle: true,
                            padding: 15,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                        },
                        ticks: {
                            font: { weight: '600', size: 11 },
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: { weight: '600', size: 11 },
                            maxRotation: 45,
                            minRotation: 45,
                        }
                    }
                }
            }
        });
    }

    function changePeriod(period) {
        // Update active button
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-white', 'text-gray-900', 'shadow-sm');
            btn.classList.add('text-gray-600');
        });
        const activeBtn = document.querySelector(`[data-period="${period}"]`);
        activeBtn.classList.add('active', 'bg-white', 'text-gray-900', 'shadow-sm');
        activeBtn.classList.remove('text-gray-600');

        // Show/hide period data
        document.querySelectorAll('.period-data').forEach(el => {
            el.classList.add('hidden');
        });
        document.querySelectorAll(`.period-data[data-period="${period}"]`).forEach(el => {
            el.classList.remove('hidden');
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        initCharts();
    });
</script>

</body>
</html>
