<!-- Personal Information Card -->
<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
    <!-- Card Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-user text-purple-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Personal Information</h2>
                <p class="text-xs text-slate-500">Manage your profile details</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <button
                id="editBtn"
                type="button" 
                class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm shadow-purple-600/20"
            >
                <i class="fa fa-pencil"></i>
                Edit
            </button>

            <div class="flex items-center gap-2" id="actionBtns" style="display: none;">
                <button
                    id="saveBtn"
                    type="submit"
                    form="profileForm"
                    class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-all shadow-sm"
                >
                    <i class="fa fa-check"></i>
                    Save
                </button>
                <button
                    id="cancelBtn"
                    type="button" 
                    class="flex items-center gap-2 px-4 py-2 bg-slate-500 text-white text-sm font-medium rounded-lg hover:bg-slate-600 transition-all shadow-sm"
                >
                    <i class="fa fa-times"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="/staff/updateProfile" method="POST" id="profileForm" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- First Name -->
            <div>
                <label for="fname" class="block text-sm font-medium text-slate-700 mb-2">
                    First Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="fname" 
                    name="fname" 
                    value="{{Auth::user()->staff->fname}}"
                    required 
                    readonly
                >
            </div>

            <!-- Middle Names -->
            <div>
                <label for="mname" class="block text-sm font-medium text-slate-700 mb-2">
                    Middle Names
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="mname" 
                    name="mname" 
                    value="{{Auth::user()->staff->mname}}"
                    readonly
                >
            </div>

            <!-- Surname -->
            <div>
                <label for="lname" class="block text-sm font-medium text-slate-700 mb-2">
                    Surname <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="lname" 
                    name="lname" 
                    value="{{Auth::user()->staff->lname}}"
                    required 
                    readonly
                >
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="email" 
                    name="email" 
                    value="{{Auth::user()->email}}"
                    required 
                    readonly
                >
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="phone" 
                    name="phone" 
                    value="{{Auth::user()->phone}}"
                    required 
                    readonly
                >
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-sm font-medium text-slate-700 mb-2">
                    Gender <span class="text-red-500">*</span>
                </label>
                <select 
                    id="gender" 
                    class="profileSelect w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all cursor-not-allowed" 
                    name="gender" 
                    disabled
                >
                    <option value="M" @selected(Auth::user()->staff->gender == 'M')>Male</option>
                    <option value="F" @selected(Auth::user()->staff->gender == 'F')>Female</option>
                </select>
            </div>

            <!-- Age -->
            <div>
                <label for="age" class="block text-sm font-medium text-slate-700 mb-2">
                    Age <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="age" 
                    name="age" 
                    value="{{Auth::user()->staff->age}}"
                    required 
                    readonly
                >
            </div>

            <!-- Residence -->
            <div>
                <label for="residence" class="block text-sm font-medium text-slate-700 mb-2">
                    Residence <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="residence" 
                    name="residence" 
                    value="{{Auth::user()->staff->residence}}"
                    required 
                    readonly
                >
            </div>

            <!-- Position -->
            <div>
                <label for="position" class="block text-sm font-medium text-slate-700 mb-2">
                    Position <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="position" 
                    name="position" 
                    value="{{Auth::user()->staff->position}}"
                    required 
                    readonly
                >
            </div>

            <!-- Department -->
            <div>
                <label for="department" class="block text-sm font-medium text-slate-700 mb-2">
                    Department <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    class="profileInput w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    id="department" 
                    name="department" 
                    value="{{Auth::user()->staff->department}}"
                    required 
                    readonly
                >
            </div>

        </div>
    </form>
</div>

<!-- Security Card -->
<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mt-6">
    <!-- Card Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
            <i class="fa fa-shield text-red-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Security</h2>
            <p class="text-xs text-slate-500">Update your password</p>
        </div>
    </div>

    <!-- Password Form -->
    <form action="/staff/updatePassword" method="POST" id="passwordForm" class="p-6">
        @csrf
        <div class="space-y-6 max-w-xl">
            
            <!-- Current Password -->
            <div>
                <label for="currentPassword" class="block text-sm font-medium text-slate-700 mb-2">
                    Current Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="currentPassword" 
                        name="current_password" 
                        required 
                        class="w-full px-4 py-2.5 pr-12 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                    >
                    <button 
                        type="button" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" 
                        data-toggle="currentPassword"
                    >
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                    New Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="w-full px-4 py-2.5 pr-12 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                    >
                    <button 
                        type="button" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" 
                        data-toggle="password"
                    >
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                    Confirm New Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        class="w-full px-4 py-2.5 pr-12 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                    >
                    <button 
                        type="button" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" 
                        data-toggle="password_confirmation"
                    >
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all shadow-sm shadow-red-600/20" 
                    id="changeBtn"
                >
                    <i class="fa fa-key"></i>
                    Change Password
                </button>
            </div>

        </div>
    </form>
</div>