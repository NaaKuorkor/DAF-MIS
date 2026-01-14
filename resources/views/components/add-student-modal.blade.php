{{-- resources/views/components/add-student-modal.blade.php --}}
<div x-data="{
    modalOpen: false,
    submitForm(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const submitButton = event.target.querySelector('button[type=\'submit\']');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Adding...';
        axios.post('/register', formData, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if(response.data.success) {
                this.modalOpen = false;
                event.target.reset();
                alert(response.data.message);
                window.dispatchEvent(new CustomEvent('refreshStudentTable'));
                setTimeout(() => {
                    const tableRows = document.getElementById('tableRows');
                    if (tableRows) {
                        const dateFilter = document.getElementById('date');
                        if (dateFilter) dateFilter.click();
                    }
                }, 100);
            } else {
                alert(response.data.message || 'Registration failed');
            }
        })
        .catch(error => {
            let errorMessage = 'Registration Failed';
            if (error.response) {
                if (error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.response.data && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join(', ');
                }
            }
            alert(errorMessage);
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    }
}"
@keydown.escape.window="modalOpen = false"
class="relative">
    <button @click="modalOpen = true" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Add Student
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
                <div class="flex items-center justify-between p-6 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-white">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Add New Student</h3>
                        <p class="text-sm text-gray-500 mt-1">Fill in the student information below</p>
                    </div>
                    <button @click="modalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" @submit.prevent="submitForm($event)" class="p-6 max-h-[70vh] overflow-y-auto">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="fname" class="text-xs font-medium text-gray-700">First Name</label>
                            <input type="text" id="fname" name="fname" placeholder="Enter first name" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="mname" class="text-xs font-medium text-gray-700">Middle Name (Optional)</label>
                            <input type="text" id="mname" name="mname" placeholder="Optional" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="lname" class="text-xs font-medium text-gray-700">Surname</label>
                            <input type="text" id="lname" name="lname" placeholder="Enter surname" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="gender" class="text-xs font-medium text-gray-700">Gender</label>
                            <select id="gender" name="gender" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="email" class="text-xs font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" placeholder="student@example.com" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="age" class="text-xs font-medium text-gray-700">Age</label>
                            <input type="number" id="age" name="age" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="password" class="text-xs font-medium text-gray-700">Password</label>
                            <input type="password" id="password" minlength="8" autocomplete="new-password" name="password" placeholder="At least 8 characters" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="confirm" class="text-xs font-medium text-gray-700">Confirm Password</label>
                            <input type="password" id="confirm" name="password_confirmation" minlength="8" autocomplete="new-password" placeholder="Re-enter password" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="phone" class="text-xs font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="0240000000" required minlength="10" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="residence" class="text-xs font-medium text-gray-700">Residence</label>
                            <input type="text" id="residence" name="residence" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="referral" class="text-xs font-medium text-gray-700">Referral Source</label>
                            <select id="referral" name="referral" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="Social Media">Social Media</option>
                                <option value="Alumni">DAF Alumni</option>
                                <option value="Website">Website</option>
                                <option value="Institution">Institution</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="employment_status" class="text-xs font-medium text-gray-700">Employment Status</label>
                            <select id="employment_status" name="employment_status" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="unemployed">Unemployed</option>
                                <option value="employed">Employed</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="course" class="text-xs font-medium text-gray-700">Course</label>
                            <select id="course" name="course" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="LS101">Life Skills</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="certificate" class="text-xs font-medium text-gray-700">Certificate Required</label>
                            <select id="certificate" name="certificate" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-purple-100">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                            Add Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
