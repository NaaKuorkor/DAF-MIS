{{-- resources/views/components/add-announcement-modal.blade.php --}}
<div x-data="{
    modalOpen: false,
    submitForm(event) {
        const formData = new FormData(event.target);
        axios.post('/staff/announcements/create', formData)
            .then(response => {
                if(response.data.success) {
                    this.modalOpen = false;
                    event.target.reset();
                    alert(response.data.message);
                    // Reload announcements
                    if (typeof loadAnnouncements === 'function') {
                        loadAnnouncements();
                    } else {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                alert(error.response?.data?.message || 'Announcement creation failed');
            });
    }
}"
@keydown.escape.window="modalOpen = false"
class="relative">
    <button @click="modalOpen = true" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        New Announcement
    </button>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">

                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-white sticky top-0 z-10">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Create New Announcement</h3>
                        <p class="text-sm text-gray-500 mt-1">Share important information with students and staff</p>
                    </div>
                    <button @click="modalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form method="POST" action="" @submit.prevent="submitForm($event)" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label for="title" class="text-xs font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" placeholder="e.g. Important Course Update" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="body" class="text-xs font-medium text-gray-700">Message <span class="text-red-500">*</span></label>
                            <textarea id="body" name="body" rows="5" placeholder="Enter the announcement message..." required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="audience" class="text-xs font-medium text-gray-700">Audience <span class="text-red-500">*</span></label>
                                <select id="audience" name="audience" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                    <option value="">Select audience...</option>
                                    <option value="all">All Users</option>
                                    <option value="students">Students Only</option>
                                    <option value="staff">Staff Only</option>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label for="status" class="text-xs font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select id="status" name="status" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="expires_at" class="text-xs font-medium text-gray-700">Expires On <span class="text-red-500">*</span></label>
                            <input type="date" id="expires_at" name="expires_at" required min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-purple-100">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                            <i class="fas fa-bullhorn mr-2"></i>
                            Publish Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>