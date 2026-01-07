@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="w-full max-w-sm bg-white border border-purple-200 shadow-md rounded-xl p-8 transition-all duration-300">
    <div class="mb-8 text-center">
        <div class="w-10 h-10 bg-purple-600 text-white rounded-lg mx-auto flex items-center justify-center mb-4">
            <i class="fa-solid fa-graduation-cap text-xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-purple-600 mb-2">Login</h1>
        <p class="text-sm text-gray-500">Enter your credentials to access your portal</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

        <div class="space-y-1.5">
            <label for="email" class="text-xs font-medium text-gray-700">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="student@gmail.com"
                required
                value="{{ old('email') }}"
                class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
            >
            @error('email')
                <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="space-y-1.5">
            <div class="flex justify-between items-center">
                <label for="password" class="text-xs font-medium text-gray-700">Password</label>
                <a href="{{ route('forgotPassword') }}" class="text-xs text-gray-500 hover:text-purple-600 transition-colors">Forgot password?</a>
            </div>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                minlength="8"
                required
                autocomplete="current-password"
                class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
            >
            @error('password')
                <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-2 custom-checkbox cursor-pointer">
            <label class="flex items-center gap-2 cursor-pointer select-none group">
                <input type="checkbox" id="remember" name="remember" class="hidden peer">
                <div class="w-4 h-4 border border-gray-300 rounded flex items-center justify-center transition-colors group-hover:border-gray-400 bg-white peer-checked:bg-purple-600 peer-checked:border-purple-600">
                    <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                </div>
                <span class="text-xs text-gray-600">Remember me</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2.5 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
            Login
            <i class="fas fa-arrow-right"></i>
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500">Don't have an account? <a href="{{ route('register.form') }}" class="text-purple-600 font-medium hover:underline">Register</a></p>
    </div>
</div>

<style>
    /* Custom Checkbox */
    .custom-checkbox input:checked + div { background-color: #9333ea; border-color: #9333ea; }
    .custom-checkbox input:checked + div svg { display: block; }
</style>
@endsection
