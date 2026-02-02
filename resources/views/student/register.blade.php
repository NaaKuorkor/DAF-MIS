@extends('layouts.auth-split')

@section('title', 'Registration')

@section('heading', 'Student Registration')
@section('subheading', 'Create your academic profile to access course.')

@section('content')

    <form action="{{ route('register') }}" method="POST" class="relative">
        @csrf

        <!-- Tab 1: Personal Information -->
        <div id="first" class="page-transition space-y-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-purple-600">Personal Information</h3>
                <!-- Progress Dots -->
                <div class="flex space-x-2">
                    <div id="dot1" class="h-2.5 w-2.5 bg-purple-600 rounded-full transition-colors duration-300"></div>
                    <div id="dot2" class="h-2.5 w-2.5 bg-gray-300 rounded-full transition-colors duration-300"></div>
                </div>
            </div>

            <!-- Name Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="fname" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="fname" name="fname" placeholder="Jane" required value="{{ old('fname') }}" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                </div>
                <div class="space-y-2">
                    <label for="lname" class="block text-sm font-medium text-gray-700">Surname</label>
                    <input type="text" id="lname" name="lname" placeholder="Doe" required value="{{ old('lname') }}" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                </div>
            </div>

            <div class="space-y-2">
                <label for="mname" class="block text-sm font-medium text-gray-700">Middle Name (Optional)</label>
                <input type="text" id="mname" name="mname" placeholder="If you have any" value="{{ old('mname') }}" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="number" id="age" name="age" required value="{{ old('age') }}" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                </div>
                <div class="space-y-2">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select id="gender" name="gender" class="custom-select block w-full px-3 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                        <option value="M" @selected(old('gender') == 'M')>Male</option>
                        <option value="F" @selected(old('gender') == 'F')>Female</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="jane.doe@gmail.com" required value="{{ old('email') }}" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="At least 8 characters" minlength="8" required autocomplete="new-password" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm pr-10">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-purple-600 transition-colors" onclick="togglePassword('password', this)">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                </div>
                <p class="text-[10px] text-gray-500">Must be at least 8 characters.</p>
            </div>

            <div class="space-y-2">
                <label for="confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="confirm" name="password_confirmation" placeholder="Re-enter password" minlength="8" required autocomplete="new-password" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm pr-10">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-purple-600 transition-colors" onclick="togglePassword('confirm', this)">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="button" id="nextBtn" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-3 px-6 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 transform active:scale-95">
                    Next Step
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>

        </div>

        <!-- Tab 2: Other Information -->
        <div id="second" class="page-transition hidden space-y-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-purple-600">Other Information</h3>
                <!-- Progress Dots -->
                <div class="flex space-x-2">
                    <div id="dot1-alt" class="h-2.5 w-2.5 bg-gray-300 rounded-full transition-colors duration-300"></div>
                    <div id="dot2-alt" class="h-2.5 w-2.5 bg-purple-600 rounded-full transition-colors duration-300"></div>
                </div>
            </div>

            <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="0240000000" required minlength="10" value="{{ old('phone') }}" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
            </div>

            <div class="space-y-2">
                <label for="residence" class="block text-sm font-medium text-gray-700">Residence</label>
                <input type="text" id="residence" name="residence" value="{{ old('residence') }}" class="block w-full px-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
            </div>

            <div class="space-y-2">
                <label for="referral" class="block text-sm font-medium text-gray-700">Referral Source</label>
                <select id="referral" name="referral" class="custom-select block w-full px-3 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                    <option value="Social Media" @selected(old('referral') == 'Social Media')>Social Media</option>
                    <option value="Alumni" @selected(old('referral') == 'Alumni')>DAF Alumni</option>
                    <option value="Website" @selected(old('referral') == 'Website')>Website</option>
                    <option value="Institution" @selected(old('referral') == 'Institution')>Institution</option>
                    <option value="Other" @selected(old('referral') == 'Other')>Other</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="employment_status" class="block text-sm font-medium text-gray-700">Employment Status</label>
                <select id="employment_status" name="employment_status" class="custom-select block w-full px-3 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                    <option value="unemployed" @selected(old('employment_status') == 'unemployed')>Unemployed</option>
                    <option value="employed" @selected(old('employment_status') == 'employed')>Employed</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="course" class="block text-sm font-medium text-gray-700">Course</label>
                <select id="course" name="course" class="custom-select block w-full px-3 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                    <option value="LS101" @selected(old('course') == 'LS101')>Life Skills</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="certificate" class="block text-sm font-medium text-gray-700">Would you like to be awarded a certificate?</label>
                <select id="certificate" name="certificate" class="custom-select block w-full px-3 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                    <option value="Y" @selected(old('certificate') == 'Y')>Yes</option>
                    <option value="N" @selected(old('certificate') == 'N')>No</option>
                </select>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" id="backBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold py-3 px-6 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 transform active:scale-95">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </button>

                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-3 px-8 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 transform active:scale-95">
                    Complete Registration
                    <i class="fas fa-check"></i>
                </button>
            </div>

        </div>

    </form>

    <div class="mt-8 text-center border-t border-gray-100 pt-6">
        <p class="text-sm text-gray-500">Already have a student account? <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:underline">Log in</a></p>
    </div>

    <style>
        .custom-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
        }
        .page-transition { transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out; }
        .hidden-section { opacity: 0; transform: translateX(10px); pointer-events: none; position: absolute; }
        .visible-section { opacity: 1; transform: translateX(0); }
    </style>

    @push('scripts')
        @vite(['resources/js/register.js'])
    @endpush
@endsection
