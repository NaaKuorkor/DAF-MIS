<!-- Main View: Course Cards Grid -->
<div id="coursesView">
    <!-- Page Header + Toolbar -->
    <div class="flex flex-col gap-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Cohort Management</h1>
                <p class="text-sm text-slate-500 mt-1">Organize student intakes, manage terms, and schedule academic periods.</p>
            </div>
        </div>

        <!-- Search Toolbar -->
        <div class="flex flex-col md:flex-row gap-4 md:items-center justify-between p-1">
            <div class="relative w-full md:w-96">
                <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchCourses" placeholder="Search by course name or ID..." class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-slate-400 shadow-sm">
            </div>
            
            <div class="flex items-center gap-4 overflow-x-auto no-scrollbar pb-1 md:pb-0">
                <!-- Status Filter -->
                <div class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg shadow-sm whitespace-nowrap group hover:border-slate-300 transition-colors">
                    <i class="fa fa-filter text-slate-400 group-hover:text-slate-600"></i>
                    <span class="text-sm font-medium text-slate-600">Status:</span>
                    <select class="text-sm bg-transparent font-medium text-slate-900 outline-none cursor-pointer">
                        <option>Active</option>
                        <option>Upcoming</option>
                        <option>Archived</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Cards Grid -->
    <div id="courseCardsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Cards will be loaded here dynamically -->
    </div>
</div>

<!-- Cohorts Table View (Hidden by default) -->
<div id="cohortsTableView" style="display: none;">
    <!-- Back Button & Header -->
    <div class="mb-6">
        <button id="backToCoursesBtn" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors mb-4">
            <i class="fa fa-arrow-left"></i>
            Back to Courses
        </button>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 tracking-tight" id="courseTitle">Course Cohorts</h1>
                <p class="text-sm text-slate-500 mt-1" id="courseSubtitle">View and manage all cohorts for this course</p>
            </div>
            <button id="createCohortHeaderBtn" class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20">
                <i class="fa fa-plus"></i>
                Create New Cohort
            </button>
        </div>
    </div>

    <!-- Cohorts Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="p-4 w-12">
                            <input type="checkbox" class="custom-checkbox cursor-pointer">
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Cohort Details
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Duration
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Students
                            </div>
                        </th>
                        <th class="p-4 text-right">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="cohortsTableBody" class="divide-y divide-slate-100">
                    <!-- Rows will be loaded here dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between p-4 border-t border-slate-100 bg-white">
            <p class="text-xs text-slate-500">Showing <span class="font-medium text-slate-900" id="cohortRangeStart">1</span>-<span class="font-medium text-slate-900" id="cohortRangeEnd">5</span> of <span class="font-medium text-slate-900" id="cohortTotal">0</span> cohorts</p>
            <div class="flex items-center gap-2">
                <button id="cohortPrevBtn" class="px-3 py-1.5 text-xs font-medium text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 disabled:opacity-50 transition-colors" disabled>
                    Previous
                </button>
                <button id="cohortNextBtn" class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Cohort Modal -->
<div id="createCohortModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa fa-layer-group text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Create New Cohort</h2>
                    <p class="text-xs text-slate-500" id="modalCourseTitle">For: Course Name</p>
                </div>
            </div>
            <button id="closeModalBtn" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="createCohortForm" class="p-6">
            @csrf
            <input type="hidden" id="modal_course_id" name="course_id">
            
            <div class="space-y-6">
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                        Cohort Description <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="description" 
                        name="description" 
                        required
                        placeholder="e.g., Fall 2024 - Morning Batch"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                    >
                </div>

                <!-- Start Date & End Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="start_date" 
                            name="start_date" 
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                        >
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-slate-700 mb-2">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="end_date" 
                            name="end_date" 
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                        >
                    </div>
                </div>

                <!-- Student Limit -->
                <div>
                    <label for="student_limit" class="block text-sm font-medium text-slate-700 mb-2">
                        Student Limit (Optional)
                    </label>
                    <input 
                        type="number" 
                        id="student_limit" 
                        name="student_limit" 
                        min="1"
                        placeholder="Leave blank for unlimited"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                    >
                    <p class="text-xs text-slate-500 mt-2">Maximum number of students allowed in this cohort</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <button 
                    type="button" 
                    id="cancelModalBtn"
                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    id="submitCohortBtn"
                    class="flex items-center gap-2 px-6 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20"
                >
                    <i class="fa fa-check"></i>
                    Create Cohort
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-checkbox {
        appearance: none;
        background-color: #fff;
        margin: 0;
        width: 1.15em;
        height: 1.15em;
        border: 1px solid #cbd5e1;
        border-radius: 0.25em;
        display: grid;
        place-content: center;
        transition: all 0.2s;
    }
    .custom-checkbox::before {
        content: "";
        width: 0.65em;
        height: 0.65em;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em white;
        transform-origin: center;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }
    .custom-checkbox:checked { background-color: #9333ea; border-color: #9333ea; }
    .custom-checkbox:checked::before { transform: scale(1); }
</style>