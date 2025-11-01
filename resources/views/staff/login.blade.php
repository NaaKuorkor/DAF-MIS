@extends('layouts.app')

@session('title', 'Staff Login')

@session('content')

        <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">

            <h2 class="text-3xl text-purple-600 text-center font-bold mb-6">Admin Login</h2>

            {{--Checks for a success message from the session and displays it--}}
            @if (session('success'))
            <div class="">
                {{session('success')}}
            </div>
            @endif


            <form method="POST" action="{{ route('staff.login') }}" >
                @csrf
                <div class="mb-4">
                     <label for='email' class="block text-gray-600 ">Email</label>
                    <input type="email" id="email" name="email" required class="w-full focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 rounded-md">
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-600">Password</label>
                    <input type="password" id="password" name="password" minlength=6 class="w-full focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 rounded-md" required>
                        @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                </div>

                <div class="mb-4">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-gray-200">
                    <label for="remember">Remember me</label>
                </div>

                <div class='mb-4'>
                    <button type="submit" class='bg-purple-600 hover:bg-purple-700 text-gray-300 w-full text-center h-8 shadow rounded-md'>Login</button>
                </div>

                <div class='flex justify-center'>
                    <a href="">Forgot password?</a>
                </div>

            </form>

        </div>
@endsession
