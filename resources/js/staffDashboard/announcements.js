export default function loadAnnouncements() {
    console.log("announcements loaded");

    const announcementsList = document.getElementById('announcementsList');
    const activeCount = document.getElementById('activeCount');
    const scheduledCount = document.getElementById('scheduledCount');
    const filterAll = document.getElementById('filterAll');
    const filterDrafts = document.getElementById('filterDrafts');
    const filterScheduled = document.getElementById('filterScheduled');
    const announcementForm = document.getElementById('announcementForm');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const scheduleToggle = document.getElementById('scheduleToggle');
    const scheduleOptions = document.getElementById('scheduleOptions');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const viewModal = document.getElementById('viewAnnouncementModal');
    const closeViewModal = document.getElementById('closeViewModal');
    const editAnnouncementBtn = document.getElementById('editAnnouncementBtn');
    const deleteAnnouncementBtn = document.getElementById('deleteAnnouncementBtn');

    if (!announcementsList) {
        console.warn('Announcements elements not found');
        return () => {};
    }

    let eventListeners = [];
    let currentFilter = 'all';
    let currentPage = 1;
    let hasMorePages = false;
    let isLoading = false;
    let currentEditingId = null;

    // Initialize
    loadAnnouncementsData();
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
                params.append('filter', currentFilter === 'drafts' ? 'drafts' : 'scheduled');
            }
            
            // FIXED: Use /list endpoint for API call
            const url = `/staff/announcements/list?${params.toString()}`;
            const response = await axios.get(url);

            if (response.data.success) {
                const { data, pagination, stats } = response.data;
                
                // Update stats
                if (activeCount) activeCount.textContent = stats?.active || 0;
                if (scheduledCount) scheduledCount.textContent = stats?.scheduled || 0;

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

    function renderAnnouncementItems(announcements) {
        if (!announcements || announcements.length === 0) {
            return `
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-bullhorn text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm font-medium">No announcements found</p>
                    <p class="text-xs text-gray-400 mt-1">Create your first announcement to get started</p>
                </div>
            `;
        }

        return announcements.map(announcement => {
            const priorityBadge = getPriorityBadge(announcement.priority);
            const statusBadge = getStatusBadge(announcement.status);
            const audienceLabels = formatAudience(announcement.audience);
            const createdDate = formatDate(announcement.createdate);
            const scheduledDate = announcement.scheduled_at ? formatDate(announcement.scheduled_at) : null;

            return `
                <div class="border-b border-slate-100 last:border-b-0 p-5 hover:bg-slate-50/50 transition-colors group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                ${priorityBadge}
                                ${statusBadge}
                            </div>
                            <h3 class="text-base font-semibold text-slate-900 mb-1.5 cursor-pointer hover:text-purple-600 transition-colors" onclick="window.viewAnnouncement('${announcement.announcement_id}')">
                                ${escapeHtml(announcement.title)}
                            </h3>
                            <p class="text-sm text-slate-600 line-clamp-2 mb-3">
                                ${escapeHtml(announcement.content)}
                            </p>
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa fa-users text-slate-400"></i>
                                    ${audienceLabels}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fa fa-calendar text-slate-400"></i>
                                    ${createdDate}
                                </span>
                                ${scheduledDate ? `
                                <span class="flex items-center gap-1.5">
                                    <i class="fa fa-clock text-slate-400"></i>
                                    Scheduled: ${scheduledDate}
                                </span>
                                ` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="window.viewAnnouncement('${announcement.announcement_id}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                <i class="fa fa-eye text-sm"></i>
                            </button>
                            <button onclick="window.editAnnouncement('${announcement.announcement_id}')" class="p-1.5 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Edit">
                                <i class="fa fa-pencil text-sm"></i>
                            </button>
                            <button onclick="window.deleteAnnouncement('${announcement.announcement_id}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <i class="fa fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
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

    function getStatusBadge(status) {
        const badges = {
            'draft': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200"><i class="fa fa-edit text-[8px] mr-1.5"></i>Draft</span>',
            'scheduled': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100"><i class="fa fa-clock text-[8px] mr-1.5"></i>Scheduled</span>',
            'active': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium border border-green-100"><i class="fa fa-check-circle text-[8px] mr-1.5"></i>Active</span>',
            'expired': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-50 text-gray-700 text-xs font-medium border border-gray-100"><i class="fa fa-clock text-[8px] mr-1.5"></i>Expired</span>'
        };
        return badges[status] || badges.draft;
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

        if (filterDrafts) {
            const handler = () => {
                currentFilter = 'drafts';
                updateFilterButtons('drafts');
                loadAnnouncementsData(1, false);
            };
            filterDrafts.addEventListener('click', handler);
            eventListeners.push({ element: filterDrafts, event: 'click', handler });
        }

        if (filterScheduled) {
            const handler = () => {
                currentFilter = 'scheduled';
                updateFilterButtons('scheduled');
                loadAnnouncementsData(1, false);
            };
            filterScheduled.addEventListener('click', handler);
            eventListeners.push({ element: filterScheduled, event: 'click', handler });
        }

        // Schedule toggle
        if (scheduleToggle && scheduleOptions) {
            const handler = () => {
                scheduleOptions.style.display = scheduleToggle.checked ? 'block' : 'none';
            };
            scheduleToggle.addEventListener('change', handler);
            eventListeners.push({ element: scheduleToggle, event: 'change', handler });
        }

        // Form submission
        if (announcementForm) {
            const handler = async (e) => {
                e.preventDefault();
                await handleFormSubmit('broadcast');
            };
            announcementForm.addEventListener('submit', handler);
            eventListeners.push({ element: announcementForm, event: 'submit', handler });
        }

        // Save draft button
        if (saveDraftBtn) {
            const handler = async () => {
                await handleFormSubmit('save_draft');
            };
            saveDraftBtn.addEventListener('click', handler);
            eventListeners.push({ element: saveDraftBtn, event: 'click', handler });
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
                currentEditingId = null;
            };
            closeViewModal.addEventListener('click', handler);
            eventListeners.push({ element: closeViewModal, event: 'click', handler });
        }

        // Modal backdrop click
        if (viewModal) {
            const handler = (e) => {
                if (e.target === viewModal) {
                    viewModal.style.display = 'none';
                    currentEditingId = null;
                }
            };
            viewModal.addEventListener('click', handler);
            eventListeners.push({ element: viewModal, event: 'click', handler });
        }

        // Delete button in modal
        if (deleteAnnouncementBtn) {
            const handler = () => {
                if (currentEditingId) {
                    deleteAnnouncement(currentEditingId);
                }
            };
            deleteAnnouncementBtn.addEventListener('click', handler);
            eventListeners.push({ element: deleteAnnouncementBtn, event: 'click', handler });
        }

        // Edit button in modal
        if (editAnnouncementBtn) {
            const handler = () => {
                if (currentEditingId) {
                    editAnnouncement(currentEditingId);
                }
            };
            editAnnouncementBtn.addEventListener('click', handler);
            eventListeners.push({ element: editAnnouncementBtn, event: 'click', handler });
        }
    }

    function updateFilterButtons(activeFilter) {
        const filters = {
            'all': filterAll,
            'drafts': filterDrafts,
            'scheduled': filterScheduled
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

    async function handleFormSubmit(action) {
        const form = announcementForm;
        if (!form) return;

        const formData = new FormData(form);
        formData.append('action', action);

        // Handle scheduled_at - only include if schedule toggle is checked
        if (!scheduleToggle?.checked) {
            formData.delete('scheduled_at');
        } else if (!formData.get('scheduled_at')) {
            alert('Please select a schedule date and time');
            return;
        }

        try {
            let response;
            if (currentEditingId) {
                // Update existing - FIXED: Use correct endpoint
                response = await axios.put(`/staff/announcements/${currentEditingId}`, formData);
            } else {
                // Create new - FIXED: Use correct endpoint
                response = await axios.post('/staff/announcements/', formData);
            }

            if (response.data.success) {
                alert(response.data.message || (action === 'broadcast' ? 'Announcement broadcasted successfully!' : 'Draft saved successfully!'));
                
                // Reset form
                form.reset();
                scheduleOptions.style.display = 'none';
                if (scheduleToggle) scheduleToggle.checked = false;
                currentEditingId = null;

                // Reload announcements
                loadAnnouncementsData(1, false);
            } else {
                alert(response.data.message || 'Operation failed');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            if (error.response?.data?.errors) {
                const errors = Object.values(error.response.data.errors).flat();
                alert(errors.join('\n') || 'Validation failed');
            } else {
                alert(error.response?.data?.message || 'Operation failed');
            }
        }
    }

    // Global functions
    window.viewAnnouncement = async function(announcementId) {
        try {
            const response = await axios.get(`/staff/announcements/${announcementId}`);
            if (response.data.success) {
                const { announcement, read_rate, total_recipients } = response.data.data;
                currentEditingId = announcementId;

                // Populate modal
                const modalTitle = document.getElementById('modalAnnouncementTitle');
                const modalContent = document.getElementById('modalAnnouncementContent');
                const modalDate = document.getElementById('modalAnnouncementDate');
                const modalPriorityBadge = document.getElementById('modalPriorityBadge');
                const modalAudience = document.getElementById('modalAudience');
                const modalReadRate = document.getElementById('modalReadRate');

                if (modalTitle) modalTitle.textContent = announcement.title;
                if (modalContent) modalContent.textContent = announcement.content;
                if (modalDate) modalDate.textContent = formatDateTime(announcement.createdate);
                if (modalPriorityBadge) modalPriorityBadge.innerHTML = getPriorityBadge(announcement.priority);
                if (modalAudience) modalAudience.textContent = formatAudience(announcement.audience);
                if (modalReadRate) modalReadRate.textContent = `${read_rate}% (${announcement.read_count || 0} of ${total_recipients || 0})`;

                // Show modal
                if (viewModal) viewModal.style.display = 'flex';
            }
        } catch (error) {
            console.error('Error loading announcement:', error);
            alert('Failed to load announcement details');
        }
    };

    window.editAnnouncement = async function(announcementId) {
        try {
            const response = await axios.get(`/staff/announcements/${announcementId}`);
            if (response.data.success) {
                const announcement = response.data.data.announcement;
                currentEditingId = announcementId;

                // Populate form
                const titleInput = document.getElementById('announcementTitle');
                const contentInput = document.getElementById('announcementContent');
                const audienceSelect = document.getElementById('audienceSelect');
                const priorityRadios = document.querySelectorAll('input[name="priority"]');
                const scheduledDateTime = document.getElementById('scheduledDateTime');
                const draftStatus = document.getElementById('draftStatus');

                if (titleInput) titleInput.value = announcement.title || '';
                if (contentInput) contentInput.value = announcement.content || '';
                
                // Set audience
                if (audienceSelect) {
                    const audienceArray = Array.isArray(announcement.audience) ? announcement.audience : JSON.parse(announcement.audience || '[]');
                    Array.from(audienceSelect.options).forEach(option => {
                        option.selected = audienceArray.includes(option.value);
                    });
                }

                // Set priority
                priorityRadios.forEach(radio => {
                    radio.checked = radio.value === announcement.priority;
                });

                // Set schedule
                if (announcement.scheduled_at) {
                    if (scheduleToggle) {
                        scheduleToggle.checked = true;
                        scheduleOptions.style.display = 'block';
                    }
                    if (scheduledDateTime) {
                        const scheduledDate = new Date(announcement.scheduled_at);
                        scheduledDateTime.value = scheduledDate.toISOString().slice(0, 16);
                    }
                } else {
                    if (scheduleToggle) scheduleToggle.checked = false;
                    scheduleOptions.style.display = 'none';
                }

                // Update status label
                if (draftStatus) draftStatus.textContent = announcement.status.toUpperCase();

                // Close view modal if open
                if (viewModal) viewModal.style.display = 'none';

                // Scroll to form
                if (announcementForm) {
                    announcementForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        } catch (error) {
            console.error('Error loading announcement for edit:', error);
            alert('Failed to load announcement for editing');
        }
    };

    window.deleteAnnouncement = async function(announcementId) {
        // Use dialog similar to staff/student deletion
        const announcementData = { announcement_id: announcementId };
        const announcementDataStr = JSON.stringify(announcementData).replace(/'/g, "\\'");
        
        const deleteDialog = `
        <div x-data='{
            modalOpen: true,
            announcement: ${announcementDataStr},
            async handleDelete() {
                try {
                    const response = await axios.delete('/staff/announcements/' + this.announcement.announcement_id);
                    if (response.data.success) {
                        this.modalOpen = false;
                        if (window.loadAnnouncements) window.loadAnnouncements();
                    } else {
                        alert(response.data.message || 'Failed to delete announcement');
                    }
                } catch (error) {
                    console.error('Delete failed:', error);
                    alert(error.response?.data?.message || 'Failed to delete announcement');
                }
            }
        }'>
            <template x-teleport="body">
                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
                    <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                    <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Delete Announcement?</h3>
                            <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to delete this announcement? This action cannot be undone.</p>
                            <div class="flex items-center gap-3">
                                <button @click="modalOpen = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                                <button @click="handleDelete()" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>`;

        // Remove any existing delete dialog
        const existingDialog = document.getElementById('deleteAnnouncementDialog');
        if (existingDialog) existingDialog.remove();

        // Create and append dialog
        const dialogContainer = document.createElement('div');
        dialogContainer.id = 'deleteAnnouncementDialog';
        dialogContainer.innerHTML = deleteDialog;
        document.body.appendChild(dialogContainer);

        // Initialize Alpine.js on the new element
        if (window.Alpine) {
            window.Alpine.initTree(dialogContainer);
        }
    };

    window.loadAnnouncements = loadAnnouncementsData;

    // Cleanup function
    return function cleanup() {
        eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });
        eventListeners = [];

        // Remove delete dialog if exists
        const deleteDialog = document.getElementById('deleteAnnouncementDialog');
        if (deleteDialog) deleteDialog.remove();

        delete window.viewAnnouncement;
        delete window.editAnnouncement;
        delete window.deleteAnnouncement;
        delete window.loadAnnouncements;

        console.log('Announcements module cleaned up');
    };
}