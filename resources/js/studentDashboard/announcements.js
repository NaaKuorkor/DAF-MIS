// resources/js/studentDashboard/announcements.js

export default function loadStudentAnnouncements() {
    console.log("student announcements loaded");

    const announcementsList = document.getElementById('announcementsList');
    const unreadCount = document.getElementById('unreadCount');
    const filterAll = document.getElementById('filterAll');
    const filterUnread = document.getElementById('filterUnread');
    const filterRead = document.getElementById('filterRead');
    const searchInput = document.getElementById('searchInput');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const viewModal = document.getElementById('viewAnnouncementModal');
    const closeViewModal = document.getElementById('closeViewModal');
    const closeViewModalBtn = document.getElementById('closeViewModalBtn');

    if (!announcementsList) {
        console.warn('Announcements elements not found');
        return () => {};
    }

    let eventListeners = [];
    let currentFilter = 'all';
    let currentPage = 1;
    let hasMorePages = false;
    let isLoading = false;
    let searchQuery = '';

    // Initialize
    loadAnnouncementsData();
    loadUnreadCount();
    setupEventListeners();

    async function loadAnnouncementsData(page = 1, append = false) {
        if (isLoading) return;

        isLoading = true;
        currentPage = page;

        if (!append) {
            announcementsList.innerHTML = `
                <div class="p-8 text-center">
                    <i class="fas fa-spinner fa-spin text-3xl text-purple-600 mb-3"></i>
                    <p class="text-gray-500">Loading announcements...</p>
                </div>
            `;
        }

        try {
            const params = new URLSearchParams();
            if (currentFilter !== 'all') {
                params.append('filter', currentFilter);
            }
            if (searchQuery) {
                params.append('search', searchQuery);
            }
            params.append('per_page', '15');

            const url = `/announcements/recipients/list?${params.toString()}`;
            const response = await axios.get(url);

            if (response.data.success) {
                const { data, pagination } = response.data;

                // Render announcements
                if (append && data.length > 0) {
                    const existingHTML = announcementsList.innerHTML;
                    announcementsList.innerHTML = existingHTML + renderAnnouncementItems(data);
                } else {
                    announcementsList.innerHTML = renderAnnouncementItems(data);
                }

                // Update pagination
                hasMorePages = pagination?.current_page < pagination?.last_page;
                if (loadMoreBtn) {
                    loadMoreBtn.style.display = hasMorePages ? 'block' : 'none';
                }

                // Update unread count
                loadUnreadCount();
            } else {
                throw new Error(response.data.message || 'Failed to load announcements');
            }
        } catch (error) {
            console.error('Error loading announcements:', error);
            if (!append) {
                announcementsList.innerHTML = `
                    <div class="p-8 text-center text-red-500">
                        <i class="fas fa-exclamation-triangle text-4xl mb-3"></i>
                        <p class="text-sm">Failed to load announcements</p>
                        <p class="text-xs text-gray-500 mt-1">${error.response?.data?.message || error.message}</p>
                    </div>
                `;
            }
        } finally {
            isLoading = false;
        }
    }

    async function loadUnreadCount() {
        try {
            const response = await axios.get('/announcements/recipients/unread-count');
            if (response.data.success && unreadCount) {
                unreadCount.textContent = response.data.unread_count || 0;
            }
        } catch (error) {
            console.error('Error loading unread count:', error);
        }
    }

    function renderAnnouncementItems(announcements) {
        if (!announcements || announcements.length === 0) {
            return `
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-bullhorn text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm font-medium">No announcements found</p>
                    <p class="text-xs text-gray-400 mt-1">Check back later for updates</p>
                </div>
            `;
        }

        return announcements.map(announcement => {
            const priorityBadge = getPriorityBadge(announcement.priority);
            const audienceLabels = formatAudience(announcement.audience);
            const publishedDate = formatDate(announcement.published_at || announcement.createdate);
            const isUnread = !announcement.is_read;
            const unreadIndicator = isUnread ? '<span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>' : '';

            return `
                <div class="border-b border-slate-100 last:border-b-0 p-5 hover:bg-slate-50/50 transition-colors group relative ${isUnread ? 'bg-purple-50/30' : ''}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                ${priorityBadge}
                                ${isUnread ? '<span class="inline-flex items-center px-2 py-1 rounded-md bg-purple-100 text-purple-700 text-xs font-medium border border-purple-200">New</span>' : ''}
                            </div>
                            <h3 class="text-base font-semibold text-slate-900 mb-1.5 cursor-pointer hover:text-purple-600 transition-colors" onclick="window.viewStudentAnnouncement('${announcement.announcement_id}')">
                                ${escapeHtml(announcement.title)}
                            </h3>
                            <p class="text-sm text-slate-600 line-clamp-2 mb-3">
                                ${escapeHtml(announcement.content)}
                            </p>
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa fa-calendar text-slate-400"></i>
                                    ${publishedDate}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fa fa-users text-slate-400"></i>
                                    ${audienceLabels}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="window.viewStudentAnnouncement('${announcement.announcement_id}')" class="p-1.5 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="View Details">
                                <i class="fa fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                    ${unreadIndicator}
                </div>
            `;
        }).join('');
    }

    function getPriorityBadge(priority) {
        const badges = {
            'info': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-purple-50 text-purple-700 text-xs font-medium border border-purple-100"><i class="fa fa-circle text-purple-500 text-[6px] mr-1.5"></i>Info</span>',
            'alert': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100"><i class="fa fa-circle text-amber-500 text-[6px] mr-1.5"></i>Alert</span>',
            'urgent': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-red-50 text-red-700 text-xs font-medium border border-red-100"><i class="fa fa-circle text-red-500 text-[6px] mr-1.5"></i>Urgent</span>'
        };
        return badges[priority] || badges.info;
    }

    function formatAudience(audience) {
        if (!audience) return 'Everyone';
        const audienceArray = Array.isArray(audience) ? audience : JSON.parse(audience || '[]');

        const labels = {
            'all_staff': 'All Staff',
            'all_students': 'All Students',
            'dept_heads': 'Department Heads',
            'academic_staff': 'Academic Staff',
            'everyone': 'Everyone'
        };

        if (audienceArray.length === 0) return 'Everyone';
        if (audienceArray.length === 1) return labels[audienceArray[0]] || audienceArray[0];
        return `${audienceArray.length} groups`;
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'N/A';
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        } catch (e) {
            return 'N/A';
        }
    }

    function formatDateTime(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'N/A';
            return date.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        } catch (e) {
            return 'N/A';
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function setupEventListeners() {
        // Filter buttons
        if (filterAll) {
            const handler = () => {
                currentFilter = 'all';
                updateFilterButtons('all');
                loadAnnouncementsData(1, false);
            };
            filterAll.addEventListener('click', handler);
            eventListeners.push({ element: filterAll, event: 'click', handler });
        }

        if (filterUnread) {
            const handler = () => {
                currentFilter = 'unread';
                updateFilterButtons('unread');
                loadAnnouncementsData(1, false);
            };
            filterUnread.addEventListener('click', handler);
            eventListeners.push({ element: filterUnread, event: 'click', handler });
        }

        if (filterRead) {
            const handler = () => {
                currentFilter = 'read';
                updateFilterButtons('read');
                loadAnnouncementsData(1, false);
            };
            filterRead.addEventListener('click', handler);
            eventListeners.push({ element: filterRead, event: 'click', handler });
        }

        // Search input
        if (searchInput) {
            let searchTimeout;
            const handler = (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = e.target.value;
                    loadAnnouncementsData(1, false);
                }, 500);
            };
            searchInput.addEventListener('input', handler);
            eventListeners.push({ element: searchInput, event: 'input', handler });
        }

        // Load more button
        if (loadMoreBtn) {
            const handler = () => {
                loadAnnouncementsData(currentPage + 1, true);
            };
            loadMoreBtn.addEventListener('click', handler);
            eventListeners.push({ element: loadMoreBtn, event: 'click', handler });
        }

        // Modal close
        if (closeViewModal) {
            const handler = () => {
                viewModal.style.display = 'none';
            };
            closeViewModal.addEventListener('click', handler);
            eventListeners.push({ element: closeViewModal, event: 'click', handler });
        }

        if (closeViewModalBtn) {
            const handler = () => {
                viewModal.style.display = 'none';
            };
            closeViewModalBtn.addEventListener('click', handler);
            eventListeners.push({ element: closeViewModalBtn, event: 'click', handler });
        }

        // Modal backdrop click
        if (viewModal) {
            const handler = (e) => {
                if (e.target === viewModal) {
                    viewModal.style.display = 'none';
                }
            };
            viewModal.addEventListener('click', handler);
            eventListeners.push({ element: viewModal, event: 'click', handler });
        }
    }

    function updateFilterButtons(activeFilter) {
        const filters = {
            'all': filterAll,
            'unread': filterUnread,
            'read': filterRead
        };

        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                if (key === activeFilter) {
                    filters[key].classList.add('border-purple-600', 'text-slate-900');
                    filters[key].classList.remove('text-slate-500');
                } else {
                    filters[key].classList.remove('border-purple-600', 'text-slate-900');
                    filters[key].classList.add('text-slate-500');
                }
            }
        });
    }

    // Global functions
    window.viewStudentAnnouncement = async function(announcementId) {
        try {
            // Mark as read and get announcement
            const response = await axios.get(`/announcements/recipients/${announcementId}`);
            if (response.data.success) {
                const { announcement } = response.data.data;

                // Populate modal
                const modalTitle = document.getElementById('modalAnnouncementTitle');
                const modalContent = document.getElementById('modalAnnouncementContent');
                const modalDate = document.getElementById('modalAnnouncementDate');
                const modalPriorityBadge = document.getElementById('modalPriorityBadge');
                const modalCreator = document.getElementById('modalAnnouncementCreator');

                if (modalTitle) modalTitle.textContent = announcement.title;
                if (modalContent) modalContent.textContent = announcement.content;
                if (modalDate) modalDate.textContent = formatDateTime(announcement.published_at || announcement.createdate);
                if (modalPriorityBadge) modalPriorityBadge.innerHTML = getPriorityBadge(announcement.priority);

                // Get creator name
                if (modalCreator && announcement.creator) {
                    modalCreator.textContent = announcement.creator.email || 'System';
                } else if (modalCreator) {
                    modalCreator.textContent = 'System';
                }

                // Show modal
                if (viewModal) viewModal.style.display = 'flex';

                // Reload announcements to update read status
                loadAnnouncementsData(currentPage, false);
                loadUnreadCount();
            }
        } catch (error) {
            console.error('Error loading announcement:', error);
            alert('Failed to load announcement details');
        }
    };

    // Cleanup function
    return function cleanup() {
        eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });
        eventListeners = [];

        delete window.viewStudentAnnouncement;

        console.log('Student announcements module cleaned up');
    };
}
