<div x-data="{ modalOpen: false }"
    @keydown.escape.window="modalOpen = false"
    class="relative z-50 w-auto h-auto">
    <button @click="modalOpen=true" class="rounded-md bg-green-400 hover:bg-green-500 p-2">Add staff</button>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
            <div x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="modalOpen=false" class="absolute inset-0 w-full h-full bg-black/40"></div>
            <div x-show="modalOpen"
                x-trap.inert.noscroll="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative px-7 py-6 w-full bg-white sm:max-w-lg sm:rounded-lg">
                <div class="flex justify-between items-center pb-2">
                    <h3 class="text-lg font-semibold">Add new staff</h3>
                    <button @click="modalOpen=false" class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800 bg-green-400 hover:bg-green-500">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="relative w-auto">
                    <form method='POST'  action="{{route('register.staff')}}">
                        @csrf

                        <div class="flex mb-4 space-x-6">
                            <div>
                                <label for="fname" class="block text-gray-600">First Name</label>
                                <input type="text" id="fname" name="fname" placeholder="Enter first name" required value="{{ old('fname') }}" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>
                            <div>
                                <label for="mname" class="block text-gray-600">Middle Name</label>
                                <input type="text" id="mname" name="mname" placeholder="Optional" value="{{ old('mname') }}"class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>
                            <div>
                               <label for="lname" class="block text-gray-600">Surname</label>
                                <input type="text" id="lname" name="lname" placeholder="Enter surname" required value="{{ old('lname') }}" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>

                        </div>

                        <div class="flex mb-4 space-x-6">
                            <div>
                                <label for='gender' class="block text-gray-600">Gender</label>
                                <select id="gender" name="gender" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                                    <option value="M" @selected(old('gender') == 'M')>Male</option>
                                    <option value="F" @selected(old('gender') == 'F')>Female</option>
                                </select>
                            </div>
                            <div>
                                <label for="email" class="block text-gray-600">Email</label>
                                <input type="email" id="email" name="email" placeholder="eg.123@gmail.com" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>
                            <div>
                                 <label for="age" class="block text-gray-600">Age</label>
                                <input type="number" id="age" name="age" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>
                        </div>

                        <div class="flex mb-4 space-x-6">
                            <div>
                                 <label for="password" class="block text-gray-600">Password</label>
                                 <input type="password" id="password" minlength=8 autocomplete="new-password" name="password" placeholder="At least 8 characters" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>

                            <div>
                                <label for="confirm" class="block text-gray-600">Re-enter Password</label>
                                <input type="password" id="confirm" name="password_confirmation" minlength=8 autocomplete="new-password" placeholder="Confirm password" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>
                            <div>
                                <label for="phone" class="block text-gray-600">Phone Number</label>
                                <input type="number" id="phone" name="phone" placeholder="eg.0240000000" required minlength="10" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>
                        </div>

                        <div class="flex mb-4 space-x-6">
                            <div>
                                 <label for="fname" class="block text-gray-600">Position</label>
                                <input type="text" id="position" name="position" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">
                            </div>
                            <div>
                                 <label for="fname" class="block text-gray-600">Department</label>
                                <input type="text" id="department" name="department" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">

                            </div>
                            <div>
                                 <label for="fname" class="block text-gray-600">Residence</label>
                                <input type="text" id="residence" name="residence" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">

                            </div>
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Register</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
