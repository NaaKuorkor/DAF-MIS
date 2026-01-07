<div class="content-fade">
    <!-- Page Header -->
    @php
        $user = Auth::user();
        // Get user display name
        $userName = ($user->student->fname . ' ' . $user->student->lname);
    @endphp
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">My Course</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ $userName }}.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="course-stats-grid">
        <!-- Cards will be populated by JS -->
        <div class="bg-white p-6 rounded-xl border border-purple-200 shadow-sm">
            <div class="animate-pulse">
                <div class="w-10 h-10 bg-gray-200 rounded-lg mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-2/3 mb-2"></div>
                <div class="h-6 bg-gray-200 rounded w-1/2"></div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-purple-200 shadow-sm">
            <div class="animate-pulse">
                <div class="w-10 h-10 bg-gray-200 rounded-lg mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-2/3 mb-2"></div>
                <div class="h-6 bg-gray-200 rounded w-1/2"></div>
            </div>
        </div>
    </div>

    <!-- Course Details Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="course-details-section" style="display: none;">
        <div class="lg:col-span-2 bg-white rounded-xl border border-purple-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Information</h3>
            <div id="course-info-content">
                <p class="text-sm text-gray-500">Loading course details...</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-purple-200 shadow-sm p-6 flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cohort Details</h3>
            <div id="cohort-info-content" class="flex-1">
                <p class="text-sm text-gray-500">Loading cohort details...</p>
            </div>
        </div>
    </div>
</div>
