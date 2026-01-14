<div class="content-fade">
    <!-- Page Title & Stats -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Announcements</h1>
            <p class="text-sm text-slate-500 mt-1">Manage broadcasts and notify staff or student groups.</p>
        </div>
        <div class="flex gap-4">
            <div class="px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                <div class="flex flex-col">
                    <span class="text-xs text-slate-500 uppercase tracking-wider font-medium">Active</span>
                    <span class="text-lg font-semibold leading-none tracking-tight" id="activeCount">0</span>
                </div>
            </div>
            <div class="px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <div class="flex flex-col">
                    <span class="text-xs text-slate-500 uppercase tracking-wider font-medium">Scheduled</span>
                    <span class="text-lg font-semibold leading-none tracking-tight" id="scheduledCount">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Layout: List vs Compose -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT COLUMN: Announcement History & Management (8 cols) -->
        <div class="lg:col-span-8 flex flex-col gap-4">
            
            <!-- Filters -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <div class="flex gap-6">
                    <button id="filterAll" class="text-sm font-medium text-slate-900 border-b-2 border-purple-600 pb-2.5 -mb-2.5">All Broadcasts</button>
                    <button id="filterDrafts" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">Drafts</button>
                    <button id="filterScheduled" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">Scheduled</button>
                </div>
                <button class="flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 transition-colors">
                    <i class="fa fa-filter text-xs"></i>
                    Filter View
                </button>
            </div>

            <!-- Announcement List -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" id="announcementsList">
                <!-- Items will be loaded here dynamically -->
                <div class="p-8 text-center">
                    <i class="fas fa-spinner fa-spin text-3xl text-purple-600 mb-3"></i>
                    <p class="text-gray-500">Loading announcements...</p>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center mt-2">
                <button id="loadMoreBtn" class="text-xs text-slate-500 hover:text-slate-800 font-medium py-2 px-4 border border-transparent hover:bg-white hover:shadow-sm hover:border-slate-200 rounded-md transition-all" style="display: none;">
                    Load older announcements
                </button>
            </div>
        </div>

        <!-- RIGHT COLUMN: Create New (4 cols) -->
        <div class="lg:col-span-4 sticky top-0">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col gap-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                        <i class="fa fa-pen-to-square text-slate-400"></i>
                        Compose
                    </h2>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide" id="draftStatus">Draft</span>
                </div>

                <!-- Form -->
                <form id="announcementForm" class="space-y-4">
                    @csrf
                    
                    <!-- Title Input -->
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Headline</label>
                        <input 
                            type="text" 
                            name="title"
                            id="announcementTitle"
                            placeholder="e.g. Exam Schedule Change" 
                            required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-slate-400 font-medium"
                        >
                    </div>

                    <!-- Audience Selector -->
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Target Audience</label>
                        <div class="relative">
                            <select 
                                name="audience"
                                id="audienceSelect"
                                required
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            >
                                <option value="all_staff">Only Staff</option>
                                <option value="all_students">Only Students</option>
                                <option value="everyone" selected>Everyone</option>
                            </select>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5">Select who should receive this announcement</p>
                    </div>

                    <!-- Content Area -->
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <label class="block text-xs font-medium text-slate-500">Message Body</label>
                            <div class="flex gap-1">
                                <button type="button" class="p-1 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-700">
                                    <i class="fa fa-bold text-xs"></i>
                                </button>
                                <button type="button" class="p-1 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-700">
                                    <i class="fa fa-italic text-xs"></i>
                                </button>
                                <button type="button" class="p-1 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-700">
                                    <i class="fa fa-link text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <textarea 
                            name="content"
                            id="announcementContent"
                            rows="6" 
                            required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-slate-400 resize-none" 
                            placeholder="Write your announcement here..."
                        ></textarea>
                    </div>

                    <!-- Priority Selection -->
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="info" class="peer sr-only" checked>
                            <div class="text-center py-2 border border-slate-200 rounded-md text-xs font-medium text-slate-600 peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 transition-all">Info</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="alert" class="peer sr-only">
                            <div class="text-center py-2 border border-slate-200 rounded-md text-xs font-medium text-slate-600 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all">Alert</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="urgent" class="peer sr-only">
                            <div class="text-center py-2 border border-slate-200 rounded-md text-xs font-medium text-slate-600 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all">Urgent</div>
                        </label>
                    </div>

                    <!-- Schedule Option -->
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fa fa-clock text-slate-400"></i>
                            <span class="text-xs text-slate-600">Schedule for later</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="scheduleToggle" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <!-- Schedule Date/Time (Hidden by default) -->
                    <div id="scheduleOptions" style="display: none;" class="space-y-3 pt-2">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Schedule Date</label>
                            <input 
                                type="datetime-local" 
                                name="scheduled_at"
                                id="scheduledDateTime"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            >
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-2">
                        <button 
                            type="button"
                            id="saveDraftBtn"
                            class="flex-1 py-2 px-4 bg-white border border-slate-200 text-slate-700 rounded-lg text-xs font-medium hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm"
                        >
                            Save Draft
                        </button>
                        <button 
                            type="submit"
                            id="broadcastBtn"
                            class="flex-1 py-2 px-4 bg-purple-600 text-white rounded-lg text-xs font-medium hover:bg-purple-700 transition-all shadow-md flex items-center justify-center gap-2"
                        >
                            <i class="fa fa-paper-plane text-xs"></i>
                            Broadcast
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

<!-- View/Edit Announcement Modal -->
<div id="viewAnnouncementModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa fa-bullhorn text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Announcement Details</h2>
                    <p class="text-xs text-slate-500" id="modalAnnouncementDate"></p>
                </div>
            </div>
            <button id="closeViewModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <div>
                <span id="modalPriorityBadge" class="inline-block mb-2"></span>
                <h3 id="modalAnnouncementTitle" class="text-xl font-semibold text-slate-900 mb-3"></h3>
                <p id="modalAnnouncementContent" class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap"></p>
            </div>

            <div class="pt-4 border-t border-slate-200 space-y-3">
                <div class="flex items-center gap-3">
                    <i class="fa fa-users text-slate-400"></i>
                    <div>
                        <p class="text-xs text-slate-500">Target Audience</p>
                        <p id="modalAudience" class="text-sm font-medium text-slate-900"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa fa-eye text-slate-400"></i>
                    <div>
                        <p class="text-xs text-slate-500">Read Rate</p>
                        <p id="modalReadRate" class="text-sm font-medium text-slate-900"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3">
            <button id="deleteAnnouncementBtn" class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fa fa-trash mr-2"></i>
                Delete
            </button>
            <button id="editAnnouncementBtn" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all">
                <i class="fa fa-pencil mr-2"></i>
                Edit
            </button>
        </div>
    </div>
</div>