// resources/js/staffDashboard/taskMngt.js

export default function loadTasks() {
    console.log("taskMngt loaded");

    const taskRows = document.getElementById('taskRows');
    const searchTask = document.getElementById('searchTask');
    const filterButtons = document.querySelectorAll('[data-filter]');
    const clearFilterBtn = document.getElementById('clearTaskFilter');

    if (!taskRows) {
        console.warn('Task management elements not found');
        return () => {};
    }

    let eventListeners = [];
    let globalFunctions = {};
    let currentFilters = {
        status: null,
        priority: null,
        search: null
    };

    // Initialize
    loadTasksData();
    setupEventListeners();

    async function loadTasksData() {
        // Show loading state
        taskRows.innerHTML = `
            <tr>
                <td colspan="5" class="p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-purple-600 mb-3"></i>
                        <p class="text-gray-500">Loading tasks...</p>
                    </div>
                </td>
            </tr>
        `;

        try {
            const params = new URLSearchParams();
            if (currentFilters.status) params.append('status', currentFilters.status);
            if (currentFilters.priority) params.append('priority', currentFilters.priority);
            if (currentFilters.search) params.append('search', currentFilters.search);

            const url = `/staff/tasks/list${params.toString() ? '?' + params.toString() : ''}`;
            const response = await axios.get(url);

            if (response.data.success) {
                renderTasks(response.data.data || []);
            } else {
                throw new Error(response.data.message || 'Failed to load tasks');
            }
        } catch (error) {
            console.error('Error loading tasks:', error);
            taskRows.innerHTML = `
                <tr>
                    <td colspan="5" class="p-8 text-center text-red-500">
                        <i class="fas fa-exclamation-triangle text-4xl mb-3"></i>
                        <p class="text-sm">Failed to load tasks</p>
                        <p class="text-xs text-gray-500 mt-1">${error.response?.data?.message || error.message}</p>
                    </td>
                </tr>
            `;
        }
    }

    function renderTasks(tasks) {
        if (!taskRows) return;

        if (!tasks || tasks.length === 0) {
            taskRows.innerHTML = `
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                        <p class="text-sm">No tasks found</p>
                    </td>
                </tr>
            `;
            updateTaskCount(0);
            return;
        }

        taskRows.innerHTML = tasks.map(task => `
            <tr class="group hover:bg-purple-50/50 transition-colors">
                <td class="p-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">${escapeHtml(task.title)}</p>
                        ${task.description ? `<p class="text-xs text-gray-500 mt-1 line-clamp-1">${escapeHtml(task.description)}</p>` : ''}
                    </div>
                </td>
                <td class="p-4">
                    <div class="text-sm text-gray-700">${formatDate(task.due_date)}</div>
                    ${isOverdue(task.due_date, task.status) ? '<div class="text-xs text-red-500 mt-1">Overdue</div>' : ''}
                </td>
                <td class="p-4">
                    ${getPriorityBadge(task.priority)}
                </td>
                <td class="p-4">
                    ${getStatusBadge(task.status)}
                </td>
                <td class="p-4 text-right">
                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        ${task.status !== 'completed' ? `
                            <button onclick="window.markTaskComplete('${task.task_id}')" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Mark as Completed">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        ` : ''}
                        ${getEditButton(task)}
                        ${getDeleteButton(task)}
                    </div>
                </td>
            </tr>
        `).join('');

        // Initialize Alpine.js on the new content
        if (window.Alpine) {
            window.Alpine.initTree(taskRows);
        }

        updateTaskCount(tasks.length);
    }

    function getEditButton(task) {
        // Format the date for the date input (YYYY-MM-DD format)
        const formattedTask = {
            ...task,
            due_date: task.due_date ? task.due_date.split(' ')[0].split('T')[0] : ''
        };
        const taskData = JSON.stringify(formattedTask).replace(/'/g, "\\'");

        return `
        <div x-data='{
            modalOpen: false,
            task: ${taskData}
        }'>
            <button @click="modalOpen = true" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Task">
                <i class="fas fa-pencil text-lg"></i>
            </button>
            ${getEditModal()}
        </div>`;
    }

    function getEditModal() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        return `
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
                <!-- Backdrop -->
                <div x-show="modalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="modalOpen = false"
                    class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

                <!-- Modal -->
                <div x-show="modalOpen"
                    x-trap.inert.noscroll="modalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">

                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-purple-100 flex items-center justify-between bg-purple-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-purple-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Edit Task</h2>
                                <p class="text-xs text-gray-500">Update task details</p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="/staff/tasks/update" @submit.prevent="
                        axios.post('/staff/tasks/' + task.task_id, new FormData($event.target))
                            .then(r => {
                                if(r.data.success) {
                                    modalOpen=false;
                                    toast.success(r.data.message || 'Task updated successfully!');
                                    if(window.loadTasks) window.loadTasks();
                                } else {
                                    toast.error(r.data.message || 'Task update failed');
                                }
                            })
                            .catch(e => {
                                if (e.response?.data?.errors) {
                                    const errors = Object.values(e.response.data.errors).flat();
                                    toast.error(errors[0] || 'Validation failed');
                                } else {
                                    toast.error(e.response?.data?.message || 'Task update failed');
                                }
                            })
                    " class="p-6 max-h-[70vh] overflow-y-auto">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="edit_task_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="edit_task_title"
                                    name="title"
                                    x-model="task.title"
                                    required
                                    placeholder="Enter task title"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                                >
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="edit_task_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    id="edit_task_description"
                                    name="description"
                                    x-model="task.description"
                                    rows="4"
                                    placeholder="Enter task description (optional)"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all resize-none"
                                ></textarea>
                            </div>

                            <!-- Due Date & Priority -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="edit_task_due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Due Date <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="edit_task_due_date"
                                        name="due_date"
                                        x-model="task.due_date"
                                        required
                                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                                    >
                                </div>

                                <div>
                                    <label for="edit_task_priority" class="block text-sm font-medium text-gray-700 mb-2">
                                        Priority <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="edit_task_priority"
                                        name="priority"
                                        x-model="task.priority"
                                        required
                                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                                    >
                                        <option value="">Select priority</option>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="edit_task_status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="edit_task_status"
                                    name="status"
                                    x-model="task.status"
                                    required
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-purple-100">
                            <button
                                type="button"
                                @click="modalOpen = false"
                                class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="flex items-center gap-2 px-6 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20"
                            >
                                <i class="fas fa-check"></i>
                                Update Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>`;
    }

    function getDeleteButton(task) {
        const taskData = JSON.stringify(task).replace(/'/g, "\\'");
        return `
        <div x-data='{
            modalOpen: false,
            task: ${taskData}
        }'>
            <button @click="modalOpen = true" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Task">
                <i class="fas fa-trash text-lg"></i>
            </button>
            <template x-teleport="body">
                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
                    <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                    <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Delete Task?</h3>
                            <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to delete "${escapeHtml(task.title)}"? This action cannot be undone.</p>
                            <div class="flex items-center gap-3">
                                <button @click="modalOpen = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                                <button @click="axios.delete('/staff/tasks/' + task.task_id).then(r => { if(r.data.success) { modalOpen=false; if(window.loadTasks) window.loadTasks(); }}).catch(e => { console.error('Delete failed:', e); modalOpen=false; })" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>`;
    }

    function getPriorityBadge(priority) {
        const badges = {
            'High': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-red-50 text-red-700 text-xs font-medium border border-red-100"><i class="fas fa-circle text-red-500 text-[8px] mr-1.5"></i>High</span>',
            'Medium': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100"><i class="fas fa-circle text-amber-500 text-[8px] mr-1.5"></i>Medium</span>',
            'Low': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium border border-green-100"><i class="fas fa-circle text-green-500 text-[8px] mr-1.5"></i>Low</span>'
        };
        return badges[priority] || `<span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-50 text-gray-700 text-xs font-medium">${priority || 'N/A'}</span>`;
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100"><i class="fas fa-clock text-amber-500 text-[8px] mr-1.5"></i>Pending</span>',
            'in_progress': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-medium border border-blue-100"><i class="fas fa-spinner text-blue-500 text-[8px] mr-1.5"></i>In Progress</span>',
            'completed': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium border border-green-100"><i class="fas fa-check-circle text-green-500 text-[8px] mr-1.5"></i>Completed</span>'
        };
        return badges[status?.toLowerCase()] || `<span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-50 text-gray-700 text-xs font-medium">${status || 'N/A'}</span>`;
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

    function isOverdue(dueDate, status) {
        if (!dueDate || status === 'completed') return false;
        const due = new Date(dueDate);
        const now = new Date();
        return due < now;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function updateTaskCount(count) {
        const showingCount = document.getElementById('showing-count');
        if (showingCount) {
            showingCount.textContent = count;
        }
    }

    function setupEventListeners() {
        // Search functionality
        if (searchTask) {
            let searchTimeout;
            const handler = (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentFilters.search = e.target.value.trim() || null;
                    loadTasksData();
                }, 300);
            };
            searchTask.addEventListener('input', handler);
            eventListeners.push({ element: searchTask, event: 'input', handler });
        }

        // Filter buttons
        filterButtons.forEach(btn => {
            const handler = () => {
                const filterType = btn.getAttribute('data-filter');
                const filterValue = btn.getAttribute('data-value');
                currentFilters[filterType] = filterValue;
                loadTasksData();
            };
            btn.addEventListener('click', handler);
            eventListeners.push({ element: btn, event: 'click', handler });
        });

        // Clear filter
        if (clearFilterBtn) {
            const handler = () => {
                currentFilters = { status: null, priority: null, search: null };
                if (searchTask) searchTask.value = '';
                loadTasksData();
            };
            clearFilterBtn.addEventListener('click', handler);
            eventListeners.push({ element: clearFilterBtn, event: 'click', handler });
        }
    }

    // Global functions
    globalFunctions.markTaskComplete = async function(taskId) {
        try {
            const response = await axios.post(`/staff/tasks/${taskId}/complete`);
            if (response.data.success) {
                if (window.loadTasks) {
                    window.loadTasks();
                }
            } else {
                console.error('Failed to mark task as complete:', response.data.message);
            }
        } catch (error) {
            console.error('Error marking task as complete:', error);
        }
    };

    // Make functions available globally
    window.markTaskComplete = globalFunctions.markTaskComplete;
    window.loadTasks = loadTasksData;

    // Cleanup function
    return function cleanup() {
        eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });
        eventListeners = [];

        delete window.markTaskComplete;
        delete window.loadTasks;

        console.log('Task module cleaned up');
    };
}
