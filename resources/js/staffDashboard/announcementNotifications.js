// resources/js/staffDashboard/announcementNotifications.js

export default function initAnnouncementNotifications() {
    const bellIcon = document.getElementById('announcementBellIcon');
    const dropdown = document.getElementById('announcementDropdown');
    const unreadBadge = document.getElementById('announcementUnreadBadge');
    const dropdownContent = document.getElementById('announcementDropdownContent');
    const viewAllLink = document.getElementById('viewAllAnnouncementsLink');

    if (!bellIcon || !dropdown) {
        console.warn('Announcement notification elements not found');
        return () => {};
    }

    let isDropdownOpen = false;
    let unreadCount = 0;
    let isLoading = false;
    let refreshInterval = null;

    // Initialize
    loadUnreadCount();
    setupEventListeners();
    startAutoRefresh();

    /**
     * Load unread announcement count
     */
    async function loadUnreadCount() {
        try {
            const response = await axios.get('/staff/announcements/recipients/unread-count');
            if (response.data.success) {
                unreadCount = response.data.unread_count || 0;
                updateUnreadBadge();
            }
        } catch (error) {
            console.error('Failed to load unread count:', error);
        }
    }

    /**
     * Load announcements for dropdown
     */
    async function loadAnnouncements(limit = 5) {
        if (isLoading) return;
        
        isLoading = true;
        
        try {
            const response = await axios.get('/staff/announcements/recipients/list', {
                params: {
                    filter: 'unread',
                    per_page: limit
                }
            });

            if (response.data.success) {
                const announcements = response.data.data || [];
                renderAnnouncements(announcements);
                
                // Update unread count
                unreadCount = response.data.pagination?.total || 0;
                updateUnreadBadge();
            }
        } catch (error) {
            console.error('Failed to load announcements:', error);
            if (dropdownContent) {
                dropdownContent.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <i class="fas fa-exclamation-triangle mb-2"></i>
                        <p class="text-sm">Failed to load announcements</p>
                    </div>
                `;
            }
        } finally {
            isLoading = false;
        }
    }

    /**
     * Render announcements in dropdown
     */
    function renderAnnouncements(announcements) {
        if (!dropdownContent) return;

        if (announcements.length === 0) {
            dropdownContent.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-3xl mb-3 text-gray-300"></i>
                    <p class="text-sm font-medium">No unread announcements</p>
                    <p class="text-xs text-gray-400 mt-1">You're all caught up!</p>
                </div>
            `;
            return;
        }

        const announcementsHTML = announcements.map(announcement => {
            const priorityColors = {
                'info': 'bg-purple-50 border-purple-200 text-purple-700',
                'alert': 'bg-amber-50 border-amber-200 text-amber-700',
                'urgent': 'bg-red-50 border-red-200 text-red-700'
            };

            const priorityIcons = {
                'info': 'fa-info-circle',
                'alert': 'fa-exclamation-triangle',
                'urgent': 'fa-exclamation-circle'
            };

            const priorityColor = priorityColors[announcement.priority] || priorityColors.info;
            const priorityIcon = priorityIcons[announcement.priority] || priorityIcons.info;
            const timeAgo = formatTimeAgo(announcement.published_at || announcement.createdate);
            const title = escapeHtml(announcement.title);
            const content = escapeHtml(announcement.content);
            const truncatedContent = content.length > 80 ? content.substring(0, 80) + '...' : content;

            return `
                <div class="announcement-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" 
                     data-announcement-id="${announcement.announcement_id}"
                     onclick="window.viewAnnouncementNotification('${announcement.announcement_id}')">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 rounded-full ${priorityColor} flex items-center justify-center border-2">
                                <i class="fas ${priorityIcon} text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-1">${title}</h4>
                                <span class="flex-shrink-0 text-xs text-gray-500">${timeAgo}</span>
                            </div>
                            <p class="text-xs text-gray-600 line-clamp-2 mb-2">${truncatedContent}</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${priorityColor}">
                                    ${announcement.priority.charAt(0).toUpperCase() + announcement.priority.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        dropdownContent.innerHTML = announcementsHTML;
    }

    /**
     * Format time ago
     */
    function formatTimeAgo(dateString) {
        if (!dateString) return 'Just now';
        
        try {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
            
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        } catch (e) {
            return 'Recently';
        }
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Update unread badge
     */
    function updateUnreadBadge() {
        if (!unreadBadge) return;

        if (unreadCount > 0) {
            unreadBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            unreadBadge.style.display = 'flex';
        } else {
            unreadBadge.style.display = 'none';
        }
    }

    /**
     * Toggle dropdown
     */
    function toggleDropdown() {
        isDropdownOpen = !isDropdownOpen;
        
        if (isDropdownOpen) {
            dropdown.classList.remove('hidden');
            loadAnnouncements();
        } else {
            dropdown.classList.add('hidden');
        }
    }

    /**
     * Close dropdown
     */
    function closeDropdown() {
        isDropdownOpen = false;
        dropdown.classList.add('hidden');
    }

    /**
     * View announcement notification
     */
    window.viewAnnouncementNotification = async function(announcementId) {
        try {
            // Mark as read
            await axios.post(`/staff/announcements/recipients/${announcementId}/mark-read`);
            
            // View announcement
            const response = await axios.get(`/staff/announcements/recipients/${announcementId}`);
            
            if (response.data.success) {
                const announcement = response.data.data.announcement;
                
                // Show announcement in modal or navigate
                showAnnouncementModal(announcement);
                
                // Reload dropdown
                loadAnnouncements();
                loadUnreadCount();
            }
        } catch (error) {
            console.error('Failed to view announcement:', error);
            alert('Failed to load announcement');
        }
    };

    /**
     * Show announcement modal
     */
    function showAnnouncementModal(announcement) {
        const priorityColors = {
            'info': 'bg-purple-50 border-purple-200 text-purple-700',
            'alert': 'bg-amber-50 border-amber-200 text-amber-700',
            'urgent': 'bg-red-50 border-red-200 text-red-700'
        };

        const priorityColor = priorityColors[announcement.priority] || priorityColors.info;
        const createdDate = new Date(announcement.published_at || announcement.createdate).toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        const modalHTML = `
            <div id="announcementNotificationModal" class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);">
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg ${priorityColor} flex items-center justify-center border-2">
                                <i class="fas fa-bullhorn text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Announcement</h2>
                                <p class="text-xs text-gray-500">${createdDate}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('announcementNotificationModal').remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium ${priorityColor} mb-3">
                                ${announcement.priority.charAt(0).toUpperCase() + announcement.priority.slice(1)}
                            </span>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">${escapeHtml(announcement.title)}</h3>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">${escapeHtml(announcement.content)}</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                        <button onclick="document.getElementById('announcementNotificationModal').remove()" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('announcementNotificationModal');
        if (existingModal) existingModal.remove();

        // Add new modal
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    /**
     * Setup event listeners
     */
    function setupEventListeners() {
        // Bell icon click
        bellIcon.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown();
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            if (isDropdownOpen && !dropdown.contains(e.target) && !bellIcon.contains(e.target)) {
                closeDropdown();
            }
        });

        // View all link
        if (viewAllLink) {
            viewAllLink.addEventListener('click', () => {
                closeDropdown();
                // Navigate to announcements module
                if (window.displayContent) {
                    window.displayContent('/staff/announcements');
                }
            });
        }

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isDropdownOpen) {
                closeDropdown();
            }
        });
    }

    /**
     * Start auto-refresh for unread count
     */
    function startAutoRefresh() {
        // Refresh every 2 minutes
        refreshInterval = setInterval(() => {
            loadUnreadCount();
        }, 120000);
    }

    /**
     * Cleanup function
     */
    return function cleanup() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        closeDropdown();
        delete window.viewAnnouncementNotification;
    };
}
