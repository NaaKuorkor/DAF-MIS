<div class="content-fade">
    <!-- Page Title -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Announcements</h1>
            <p class="text-sm text-slate-500 mt-1">View important announcements and updates.</p>
        </div>
        <div class="flex gap-4">
            <div class="px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                <div class="flex flex-col">
                    <span class="text-xs text-slate-500 uppercase tracking-wider font-medium">Unread</span>
                    <span class="text-lg font-semibold leading-none tracking-tight" id="unreadCount">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-200 mb-4">
        <div class="flex gap-6">
            <button id="filterAll" class="text-sm font-medium text-slate-900 border-b-2 border-purple-600 pb-2.5 -mb-2.5">All</button>
            <button id="filterUnread" class="text-sm font-medium text-slate-500 hover:text-slate-700 pb-2.5 -mb-2.5 transition-colors">Unread</button>
            <button id="filterRead" class="text-sm font-medium text-slate-500 hover:text-slate-700 pb-2.5 -mb-2.5 transition-colors">Read</button>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Search announcements..." 
                    class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400 w-64"
                >
            </div>
        </div>
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
    <div class="flex justify-center mt-4">
        <button id="loadMoreBtn" class="text-xs text-slate-500 hover:text-slate-800 font-medium py-2 px-4 border border-transparent hover:bg-white hover:shadow-sm hover:border-slate-200 rounded-md transition-all" style="display: none;">
            Load more announcements
        </button>
    </div>
</div>

<!-- View Announcement Modal -->
<div id="viewAnnouncementModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa fa-bullhorn text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Announcement</h2>
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
                    <i class="fa fa-user text-slate-400"></i>
                    <div>
                        <p class="text-xs text-slate-500">From</p>
                        <p id="modalAnnouncementCreator" class="text-sm font-medium text-slate-900"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3">
            <button id="closeViewModalBtn" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all">
                Close
            </button>
        </div>
    </div>
</div>
