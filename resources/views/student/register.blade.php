@extends('layouts.app')

@section('title', 'Registration')

@section('content')

<div class="w-full max-w-xl bg-white border border-purple-200 shadow-md rounded-xl p-8 transition-all duration-300">
    <div class="mb-6">
        <div class="w-10 h-10 bg-purple-100 text-purple-600 border border-purple-200 rounded-lg flex items-center justify-center mb-4">
            <i class="fas fa-user-plus text-xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-purple-600 mb-2">Registration</h1>
        <p class="text-sm text-gray-500">Create your academic profile to access course.</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="relative">
        @csrf

        <!-- Tab 1: Personal Information -->
        <div id="first" class="page-transition  space-y-4">

            <h3 class="text-xl text-purple-600 font-medium mb-4">Personal Information</h3>

            <!-- Progress Dots -->
            <div class="flex justify-center mb-6 space-x-2">
                <div id="dot1" class="h-3 w-3 bg-purple-600 rounded-full"></div>
                <div id="dot2" class="h-3 w-3 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Name Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="fname" class="text-xs font-medium text-gray-700">First Name</label>
                    <input type="text" id="fname" name="fname" placeholder="Jane" required value="{{ old('fname') }}" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label for="lname" class="text-xs font-medium text-gray-700">Surname</label>
                    <input type="text" id="lname" name="lname" placeholder="Doe" required value="{{ old('lname') }}" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="mname" class="text-xs font-medium text-gray-700">Middle Name (Optional)</label>
                <input type="text" id="mname" name="mname" placeholder="If you have any" value="{{ old('mname') }}" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="age" class="text-xs font-medium text-gray-700">Age</label>
                    <input type="number" id="age" name="age" required value="{{ old('age') }}" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label for="gender" class="text-xs font-medium text-gray-700">Gender</label>
                    <select id="gender" name="gender" class="custom-select w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        <option value="M" @selected(old('gender') == 'M')>Male</option>
                        <option value="F" @selected(old('gender') == 'F')>Female</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="email" class="text-xs font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="jane.doe@gmail.com" required value="{{ old('email') }}" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
            </div>

            <div class="space-y-1.5">
                <label for="password" class="text-xs font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="At least 8 characters" minlength="8" required autocomplete="new-password" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                <p class="text-[10px] text-gray-500">Must be at least 8 characters.</p>
            </div>

            <div class="space-y-1.5">
                <label for="confirm" class="text-xs font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="confirm" name="password_confirmation" placeholder="Re-enter password" minlength="8" required autocomplete="new-password" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
            </div>

            <div class="flex justify-center mt-6">
                <button type="button" id="nextBtn" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                    Next
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>

        </div>

        <!-- Tab 2: Other Information -->
        <div id="second" class="page-transition hidden space-y-4">

            <h3 class="text-xl text-purple-600 font-medium mb-4">Other Information</h3>

            <!-- Progress Dots -->
            <div class="flex justify-center mb-6 space-x-2">
                <div id="dot1" class="h-3 w-3 bg-gray-300 rounded-full"></div>
                <div id="dot2" class="h-3 w-3 bg-purple-600 rounded-full"></div>
            </div>

            <div class="space-y-1.5">
                <label for="phone" class="text-xs font-medium text-gray-700">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="0240000000" required minlength="10" value="{{ old('phone') }}" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
            </div>

            <div class="space-y-1.5">
                <label for="residence" class="text-xs font-medium text-gray-700">Residence</label>
                <input type="text" id="residence" name="residence" value="{{ old('residence') }}" class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
            </div>

            <div class="space-y-1.5">
                <label for="referral" class="text-xs font-medium text-gray-700">Referral Source</label>
                <select id="referral" name="referral" class="custom-select w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                    <option value="Social Media" @selected(old('referral') == 'Social Media')>Social Media</option>
                    <option value="Alumni" @selected(old('referral') == 'Alumni')>DAF Alumni</option>
                    <option value="Website" @selected(old('referral') == 'Website')>Website</option>
                    <option value="Institution" @selected(old('referral') == 'Institution')>Institution</option>
                    <option value="Other" @selected(old('referral') == 'Other')>Other</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="employment_status" class="text-xs font-medium text-gray-700">Employment Status</label>
                <select id="employment_status" name="employment_status" class="custom-select w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                    <option value="unemployed" @selected(old('employment_status') == 'unemployed')>Unemployed</option>
                    <option value="employed" @selected(old('employment_status') == 'employed')>Employed</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="course" class="text-xs font-medium text-gray-700">Course</label>
                <select id="course" name="course" class="custom-select w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                    <option value="LS101" @selected(old('course') == 'LS101')>Life Skills</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="certificate" class="text-xs font-medium text-gray-700">Would you like to be awarded a certificate?</label>
                <select id="certificate" name="certificate" class="custom-select w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                    <option value="Y" @selected(old('certificate') == 'Y')>Yes</option>
                    <option value="N" @selected(old('certificate') == 'N')>No</option>
                </select>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" id="backBtn" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </button>

                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                    Register
                    <i class="fas fa-check"></i>
                </button>
            </div>

        </div>

    </form>

    <div class="mt-6 text-center border-t border-gray-100 pt-6">
        <p class="text-xs text-gray-500">Already have a student account? <a href="{{ route('login') }}" class="text-purple-600 font-medium hover:underline">Log in</a></p>
    </div>
</div>

<style>
    .custom-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
    .page-transition { transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out; }
    .hidden-section { opacity: 0; transform: translateX(10px); pointer-events: none; position: absolute; }
    .visible-section { opacity: 1; transform: translateX(0); }
</style>

@vite(['resources/js/register.js'])
@endsection
