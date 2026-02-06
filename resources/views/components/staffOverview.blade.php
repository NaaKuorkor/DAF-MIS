
<div class="content-fade">
    <!-- Page Header -->
@php
            $user = Auth::user();

            // Get user display name
            $userName = ($user->staff->fname . ' ' . $user->staff->lname);
@endphp
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ $userName }}.</p>
        </div>

    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach ($cards as $card)
            <div class="bg-white p-6 rounded-xl border border-purple-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg {{ $card['bg_color'] ?? 'bg-purple-50' }} flex items-center justify-center {{ $card['text_color'] ?? 'text-purple-600' }}">
                        <i class="fas {{ $card['icon'] ?? 'fa-chart-line' }} text-xl"></i>
                    </div>
                    @if(isset($card['trend']))
                        <span class="flex items-center text-xs font-medium {{ $card['trend'] >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-full">
                            {{ $card['trend'] >= 0 ? '+' : '' }}{{ $card['trend'] }}%
                            <i class="fas {{ $card['trend'] >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} ml-1"></i>
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 font-medium">{{ $card['title'] }}</p>
                <h3 class="text-2xl font-semibold text-gray-900 mt-1 tracking-tight">{{ $card['value'] }}</h3>
            </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <!-- Charts Section -->
    <div class="mb-8">
        <div class="bg-white p-6 rounded-xl border border-purple-200 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Statistics</h3>
            <div class="relative h-64 w-full">
                <canvas id="registrationChart"></canvas>
            </div>
            <!-- Hidden element to pass data to JS -->
            <div id="registrationChartData" data-registrations="{{ json_encode($registrationData) }}" class="hidden"></div>
        </div>
    </div>

    <!-- Recent Activity Section (Optional) -->
    @if(isset($activities) && count($activities) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-purple-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
            <p class="text-sm text-gray-500">Additional analytics will appear here</p>
        </div>

        <div class="bg-white rounded-xl border border-purple-200 shadow-sm p-6 flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>

            <div class="space-y-6 overflow-y-auto no-scrollbar pr-2 flex-1">
                @foreach($activities as $activity)
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full {{ $activity['bg_color'] ?? 'bg-purple-50' }} {{ $activity['text_color'] ?? 'text-purple-600' }} flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas {{ $activity['icon'] ?? 'fa-bell' }} text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-800 font-medium">{{ $activity['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <button class="w-full mt-4 py-2 text-sm text-purple-600 font-medium border border-purple-100 rounded-lg hover:bg-purple-50 transition-colors">
                View All History
            </button>
        </div>
    </div>
    @endif
</div>
