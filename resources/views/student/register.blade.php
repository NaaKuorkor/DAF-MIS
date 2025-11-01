@extends('layouts.app')

@section('title', 'Registration')

@section('content')

<div class="bg-white shadow-md rounded-lg py-12 px-20 w-full max-w-xl">
    <h1 class="text-3xl text-purple-600 text-center font-bold mb-6">Registration</h1>

    <form method="POST" action="{{ route('register') }}" >
        @csrf

        <div id="first">

            <h3 class="text-xl text-purple-600 text-center mb-4 ">Personal Information</h3>

            <div class="flex justify-center mb-6 space-x-2">
              <div id="dot1" class="h-3 w-3 bg-purple-400 rounded-full"></div>
              <div id="dot2" class="h-3 w-3 bg-gray-300 rounded-full"></div>
            </div>

            <div class="mb-4">
                <label for="firstname" class="block text-gray-600">First Name</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter first name" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>

            <div class="mb-4">
                <label for="middlename" class="block text-gray-600">Middle Name(if any)</label>
                <input type="text" id="middlename" name="middlename" placeholder="Optional if you do not have any" class="focus:outine-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>


            <div class="mb-4">
                <label for="surname" class="block text-gray-600">Surname</label>
                <input type="text" id="surname" name="surname" placeholder="Enter surname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>

            <div class="mb-4">
                <label for='gender' class="block text-gray-600">Gender</label>
                <select id="gender" name="gender" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>


            <div class="mb-4">
                <label for="email" class="block text-gray-600">Email</label>
                <input type="email" id="email" name="email" placeholder="eg.123@gmail.com" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>

            <div class="mb-4">
                 <label for="password" class="block text-gray-600">Password</label>
                <input type="password" id="password" minlength=8 required autocomplete="new-password" name="password" placeholder="At least 8 characters" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>

            <div class="mb-8">
                <label for="confirm" class="block text-gray-600">Re-enter Password</label>
                <input type="password" id="confirm" name="password_confirmation" minlength=8 required autocomplete="new-password" placeholder="Confirm password" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>

            <div class="flex justify-center mt-4">
                <button type="button" id="nextBtn" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Next</button>
            </div>

        </div>

        <div id="second" class="hidden">

            <h3 class="text-xl text-purple-600 text-center ">Other Information</h3>

            <div class="flex justify-center mb-6 space-x-2">
              <div id="dot1" class="h-3 w-3 bg-gray-300 rounded-full"></div>
              <div id="dot2" class="h-3 w-3 bg-purple-400 rounded-full"></div>
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-600">Phone Number</label>
                <input type="number" id="phone" name="phone" placeholder="eg.0240000000" required minlength="10" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>

            <div class="mb-4">
                <label for="residence" class="block text-gray-600">Residence</label>
                <input type="text" id="residence" name="residence" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
            </div>

            <div class="mb-4">
                <label for="referral" class="block text-gray-600">Referral Source</label>
                <select id='referral' name="referral" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                    <option value="Social Media">Social Media</option>
                    <option value="Alumni">DAF Alumni</option>
                    <option value="Website">Website</option>
                    <option value="Institution">Institution</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="employment-status" class="block text-gray-600">Employment Status</label>
                <select id="employment-status" name="employment-status" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                    <option value="unemployed">Unemployed</option>
                    <option value="self-employed">Self-employed</option>
                    <option value="employed">Employed</option>
                </select>
            </div>

            <div class="mb-8">
                <label for="certificate" class="block text-gray-600">Would you like to be awarded a certificate after the course?</label>
                <select id="certificate" name="certificate" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md">
                    <option value="Y">Yes</option>
                    <option value="N">No</option>
                </select>
            </div>

            <div class="flex justify-between mt-4">
                <button type="button" id="backBtn" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Back</button>

                <button type="submit" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Register</button>
            </div>


        </div>
    </form>
</div>
@vite(['resources/js/register.js'])
@endsection
