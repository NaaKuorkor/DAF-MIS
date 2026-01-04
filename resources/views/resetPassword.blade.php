@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<div class="max-w-md w-full bg-white rounded-2xl shadow-md overflow-hidden border border-purple-200 relative">

    <!-- Decorative Top Bar -->
    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-600 to-purple-800"></div>

    <div class="p-8 pt-10">
        <!-- Icon -->
        <div class="mx-auto w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mb-6 ring-4 ring-purple-50/50">
            <i class="fas fa-lock text-3xl text-purple-600"></i>
        </div>

        <!-- Text Content -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h1>
            <p class="text-gray-500 text-sm leading-relaxed">
                Create a new password for your account
            </p>
        </div>

        <!-- Reset Password Form -->
        <form action="{{ route('') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Old Password Input -->
            <div class="space-y-1.5">
                <label for="old_password" class="text-xs font-medium text-gray-700">Old Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-key text-sm"></i>
                    </div>
                    <input
                        type="password"
                        id="old_password"
                        name="old_password"
                        placeholder="Enter old password"
                        required
                        minlength="8"
                        autocomplete="current-password"
                        class="w-full pl-9 pr-10 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                    >
                    <button type="button" onclick="togglePassword('old_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                @error('old_password')
                    <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password Input -->
            <div class="space-y-1.5">
                <label for="new_password" class="text-xs font-medium text-gray-700">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        placeholder="Enter new password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        class="w-full pl-9 pr-10 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                    >
                    <button type="button" onclick="togglePassword('new_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="text-[10px] text-gray-500">Must be at least 8 characters.</p>
                @error('new_password')
                    <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password Input -->
            <div class="space-y-1.5">
                <label for="confirm_password" class="text-xs font-medium text-gray-700">Confirm New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <input
                        type="password"
                        id="confirm_password"
                        name="new_password_confirmation"
                        placeholder="Re-enter new password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        class="w-full pl-9 pr-10 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                    >
                    <button type="button" onclick="togglePassword('confirm_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                @error('new_password_confirmation')
                    <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
            </div>

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Reset Button -->
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-purple-600/20 active:scale-[0.98] flex items-center justify-center gap-2 group mt-6">
                <span>Reset Password</span>
                <i class="fas fa-check group-hover:scale-110 transition-transform"></i>
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <a href="{{ route('login.form') }}" class="text-sm text-gray-500 hover:text-purple-600 flex items-center justify-center gap-2 transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i>
                Back to Login
            </a>
        </div>
    </div>
</div>


@vite(['resources/js/resetPassword.js'])
@endsection
