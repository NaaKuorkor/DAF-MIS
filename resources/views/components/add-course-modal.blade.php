{{-- resources/views/components/create-course-modal.blade.php --}}
<div x-data="{
    modalOpen: false,
    submitForm(event) {
        const formData = new FormData(event.target);
        axios.post('/staff/createCourse', formData)
            .then(response => {
                if(response.data.success) {
                    this.modalOpen = false;
                    alert(response.data.message);
                    location.reload();
                }
            })
            .catch(error => {
                alert(error.response?.data?.message || 'Course creation failed');
            });
    }
}"
@keydown.escape.window="modalOpen = false"
    class="relative">
<button @click="modalOpen = true" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Add Course
    </button>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">

                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-white">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Create New Course</h3>
                        <p class="text-sm text-gray-500 mt-1">Fill in the course details below</p>
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
                            <label for="course_name" class="text-xs font-medium text-gray-700">Course Name</label>
                            <input type="text" id="course_name" name="course_name" placeholder="e.g. Introduction to Computer Science" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="description" class="text-xs font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Brief description of the course" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="duration" class="text-xs font-medium text-gray-700">Duration</label>
                                <input type="text" id="duration" name="duration" placeholder="e.g. 12 weeks" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label for="eligibility" class="text-xs font-medium text-gray-700">Eligibility (Optional)</label>
                                <input type="text" id="eligibility" name="eligibility" placeholder="e.g. High School Diploma" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-purple-100">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                            Create Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
