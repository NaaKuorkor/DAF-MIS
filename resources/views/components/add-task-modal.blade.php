{{-- resources/views/components/add-task-modal.blade.php --}}
<div x-data="{
    modalOpen: false,
    submitForm(event) {
        const formData = new FormData(event.target);
        axios.post('/staff/tasks/create', formData)
            .then(response => {
                if(response.data.success) {
                    this.modalOpen = false;
                    event.target.reset();
                    // Reload tasks table
                    if (window.loadTasks) {
                        window.loadTasks();
                    }
                    alert(response.data.message || 'Task created successfully!');
                } else {
                    alert(response.data.message || 'Failed to create task');
                }
            })
            .catch(error => {
                if (error.response?.data?.errors) {
                    const errors = Object.values(error.response.data.errors).flat();
                    alert(errors.join('\n'));
                } else {
                    alert(error.response?.data?.message || 'Task creation failed');
                }
            });
    }
}"
@keydown.escape.window="modalOpen = false"
class="relative">
    <button @click="modalOpen = true" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Add Task
    </button>

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
                            <i class="fas fa-tasks text-purple-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Create New Task</h2>
                            <p class="text-xs text-gray-500">Add a new task to your list</p>
                        </div>
                    </div>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="task_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="task_title" 
                                name="title" 
                                required
                                placeholder="Enter task title"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                            >
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="task_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea 
                                id="task_description" 
                                name="description" 
                                rows="4"
                                placeholder="Enter task description (optional)"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all resize-none"
                            ></textarea>
                        </div>

                        <!-- Due Date & Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="task_due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="task_due_date" 
                                    name="due_date" 
                                    required
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                                >
                            </div>

                            <div>
                                <label for="task_priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="task_priority" 
                                    name="priority" 
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
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>