<div>
    <p>Personal Information</p>

    <form action="/staff/updateProfile" method="POST" id="profileForm">
        @csrf
        <div class="space-x-2">
            <button
                id='editBtn'
                type="button" class="bg-purple-600 text-white px-4 py-1 rounded-md">
                Edit
            </button>

            <div class="space-x-2" id='actionBtns' hidden>
                <button
                    id="saveBtn"
                    type="submit"
                    class="bg-green-600 text-white px-4 py-1 rounded-md"
                >
                    Save
                </button>
                <button
                    id='cancelBtn'
                    type="button" class="bg-gray-500 text-white px-4 py-1 rounded-md"
                >
                    Cancel
                </button>
            </div>
        </div>


        <div class='grid grid-cols-1 md:grid-cols-2'>
            <div>
                <label for="fname" class="block text-gray-600">First Name</label>
                <input type="text" class="profileInput" id="fname" name="fname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->staff->fname}}" readonly>
            </div>
            <div>
                <label for="mname" class="block text-gray-600">Middle Names</label>
                <input type="text" class="profileInput" id="mname" name="mname" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->staff->mname}}" readonly>
            </div>
            <div>
                <label for="lname" class="block text-gray-600">Surname</label>
                <input type="text" class="profileInput" id="lname" name="lname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->staff->lname}}" readonly>
            </div>
            <div>
                <label for='gender' class="block text-gray-600">Gender</label>
                <select id="gender" class="profileSelect" name="gender"  class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" disabled>
                        <option value="M" @selected( $user->staff->gender== 'M')>Male</option>
                        <option value="F" @selected($user->staff->gender == 'F')>Female</option>
                </select>
            </div>
            <div>
                 <label for="age" class="block text-gray-600">Age</label>
                <input type="text" class="profileInput"  id="age" name="age" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->staff->age}}" readonly>
            </div>
            <div>
                <label for="residence" class="block text-gray-600">Residence</label>
                <input type="text" class="profileInput" id="residence" name="residence" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->staff->residence}}" readonly>
            </div>
            <div>
                <label for="position" class="block text-gray-600">Position</label>
                <input type="text" class="profileInput" id="position" name="position" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->staff->position}}" readonly>
            </div>
            <div>
                <label for="department" class="block text-gray-600">Department</label>
                <input type="text" class="profileInput" id="department" name="department" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->staff->department}}" readonly>
            </div>
        </div>
    </form>
</div>

<div>
    <div>
        <p>Security</p>
        <form action="/staff/updatePassword" method="POST" id = 'passwordForm'>
            @csrf
            <div>
                <label for="current" class="block text-gray-600">Current Password</label>
                <input type="password" id="currentPassword" name="currentPassword" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600" data-toggle="current_password">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </button>

            </div>
            <div>
                <label for="password" class="block text-gray-600">Newpassword</label>
                <input type="password" id="password" name="password" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600" data-toggle="password">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </button>

            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-600">Confirm new password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md">
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600" data-toggle="password_confirmation">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </button>
            </div>
            <button type="submit" class="mb-4 bg-red-600 hover:bg-red-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow" id='changeBtn'>Change</button>
        </form>
    </div>
</div>
