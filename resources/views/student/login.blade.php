@extends('layouts.auth-split')

@section('title', 'Student Login')

@section('heading', 'Welcome back')
@section('subheading', 'Enter your credentials to access your student portal')

@section('content')
    {{-- Success message --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Email Field --}}
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="student@gmail.com"
                    required
                    value="{{ old('email') }}"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm"
                >
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Field --}}
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-purple-600 transition-colors" onclick="togglePassword('password', this)">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember & Forgot Password --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer">
                <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                    Remember me
                </label>
            </div>
            <a href="{{ route('forgotPassword') }}" class="text-sm font-medium text-purple-600 hover:text-purple-500 hover:underline">
                Forgot password?
            </a>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:-translate-y-0.5">
            Sign in
        </button>

        {{-- Register Link --}}
        <div class="mt-6 text-center text-sm text-gray-500">
            Don't have an account? 
            <a href="{{ route('register.form') }}" class="font-semibold text-purple-600 hover:text-purple-500 hover:underline">
                Register here
            </a>
        </div>
    </form>
@endsection



