{{-- resources/views/components/dashboard/course-management.blade.php --}}
<div class="content-fade" id="course-view">
    <!-- Page Header + Toolbar -->
    <div class="flex flex-col gap-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Course Management</h1>
                <p class="text-sm text-gray-500 mt-1">Manage courses, materials and academic requirements.</p>
            </div>
            <div class="flex gap-3">
               <x-add-course-modal />
            </div>
        </div>

        <!-- Search Toolbar -->
        <div class="flex flex-col md:flex-row gap-4 md:items-center justify-between p-1">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchCourse" placeholder="Search courses by name or code..." class="w-full pl-10 pr-4 py-2 bg-white border border-purple-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="courseGrid">
        <!-- Loading State -->
        <div class="col-span-full flex items-center justify-center py-12">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-4"></i>
                <p class="text-gray-500">Loading courses...</p>
            </div>
        </div>
    </div>
</div>

<!-- Registration View (Hidden by default) -->
<div class="content-fade hidden" id="registration-view">
    <!-- Will be populated by JavaScript -->
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@vite(['resources/js/staffDashboard/courseMngt.js'])
