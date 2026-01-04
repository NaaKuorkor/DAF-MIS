{{-- resources/views/components/add-staff-modal.blade.php --}}
<div x-data="{
    modalOpen: false,
    submitForm(event) {
        const formData = new FormData(event.target);
        axios.post('/staff/register', formData)
            .then(response => {
                if(response.data.success) {
                    this.modalOpen = false;
                    alert(response.data.message);
                    location.reload();
                }
            })
            .catch(error => {
                alert(error.response?.data?.message || 'Registration Failed');
            });
    }
}"
@keydown.escape.window="modalOpen = false"
class="relative">
    <button @click="modalOpen = true" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Add Staff
    </button>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">

                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-white">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Add New Staff Member</h3>
                        <p class="text-sm text-gray-500 mt-1">Fill in the staff information below</p>
                    </div>
                    <button @click="modalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register.staff') }}" @submit.prevent="submitForm($event)" class="p-6 max-h-[70vh] overflow-y-auto">
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
                            <input type="email" id="email" name="email" placeholder="staff@example.com" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
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
                            <label for="position" class="text-xs font-medium text-gray-700">Position</label>
                            <input type="text" id="position" name="position" placeholder="e.g. Senior Lecturer" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="department" class="text-xs font-medium text-gray-700">Department</label>
                            <input type="text" id="department" name="department" placeholder="e.g. Computer Science" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="residence" class="text-xs font-medium text-gray-700">Residence</label>
                            <input type="text" id="residence" name="residence" placeholder="Location" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-purple-100">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                            Add Staff Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
