<div>
    <p>Personal Information</p>

    <form action="/" method="POST" id='profileForm'>
        @csrf
        <div class="space-x-2">
            <button
                type="button" id="editBtn" class="bg-purple-600 text-white px-4 py-1 rounded-md">
                Edit
            </button>

            <div class="space-x-2" id="actionBtns" hidden>
                <button
                    type="submit" id='saveBtn'
                    class="bg-green-600 text-white px-4 py-1 rounded-md"
                >
                    Save
                </button>
                <button
                    type="button" id="cancelBtn" class="bg-gray-500 text-white px-4 py-1 rounded-md"
                >
                    Cancel
                </button>
            </div>
        </div>


        <div class='grid grid-cols-1 md:grid-cols-2'>

            <div>
                <label for="fname" class="block text-gray-600">First Name</label>
                <input type="text" id="fname" name="fname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->student->fname}}" readonly>
            </div>
            <div>
                <label for="mname" class="block text-gray-600">Middle Names</label>
                <input type="text" id="mname" name="mname" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->student->mname}}" readonly>
            </div>
            <div>
                <label for="lname" class="block text-gray-600">Surname</label>
                <input type="text" id="lname" name="lname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->student->lname}}" readonly>
            </div>
            <div>
                 <label for="email" class="block text-gray-600">Email</label>
                <input type="email" id="email" name="email" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->email}}" readonly>

            </div>
            <div>
                <label for="phone" class="block text-gray-600">Phone Number</label>
                <input type="text" id="phone" name="phone" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->phone}}" readonly>
            </div>
            <div>
                <label for='gender' class="block text-gray-600">Gender</label>
                <select id="gender" name="gender"  class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" disabled>
                        <option value="M" @selected( $user->student->gender== 'M')>Male</option>
                        <option value="F" @selected($user->student->gender == 'F')>Female</option>
                </select>
            </div>
            <div>
                 <label for="age" class="block text-gray-600">Age</label>
                <input type="text" id="age" name="age" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->student->age}}" readonly>
            </div>
            <div>
                <label for="residence" class="block text-gray-600">Residence</label>
                <input type="text" id="residence" name="residence" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" value="{{$user->student->residence}}" readonly>
            </div>

            <div>
                <label for="referral" class="block text-gray-600">Referral Source</label>
                <select id='referral' name="referral"   class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" disabled>
                    <option value="Social Media" @selected($user->student->referral == 'Social Media')>Social Media</option>
                    <option value="Alumni"  @selected($user->student->referral == "Alumni")>DAF Alumni</option>
                    <option value="Website"  @selected($user->student->referral == 'Website')>Website</option>
                    <option value="Institution"  @selected($user->student->referral == 'Institution')>Institution</option>
                    <option value="Other"  @selected($user->student->referral == 'Other')>Other</option>
                    </select>
            </div>

            <div>
                <label for="employment_status" class="block text-gray-600">Employment Status</label>
                    <select id="employment_status" name="employment_status" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" disabled>
                        <option value="unemployed"  @selected($user->student->employment_status == 'unemployed')>Unemployed</option>
                        <option value="employed"  @selected($user->student->employment_status == 'employed')>Employed</option>
                    </select>
            </div>
        </div>
    </form>

</div>
<div>
    <div>
        <p>Security</p>
        <form action="/" method="POST" id="passwordForm">
            @csrf
            <div>
                <label for="current_password" class="block text-gray-600">Current Password</label>
                <input type="password" id="current_password" name="current-password" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 pr-10 w-full rounded-md">
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600" data-toggle="current_password">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </button>
            </div>
            <div>
                <label for="password" class="block text-gray-600">New Password</label>
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
            <button type="submit" class="mb-4 bg-red-600 hover:bg-red-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Change</button>
        </form>
    </div>
</div>
