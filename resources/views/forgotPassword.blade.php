@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')

<div class="w-full max-w-md bg-white border border-purple-200 shadow-md rounded-xl p-8 transition-all duration-300">
    <div class="mb-6">
        <div class="w-10 h-10 bg-purple-100 text-purple-600 border border-purple-200 rounded-lg flex items-center justify-center mb-4">
            <i class="fas fa-key text-lg"></i>
        </div>
        <h1 class="text-xl font-medium tracking-tight mb-2">Reset Password</h1>
        <p class="text-sm text-gray-500 leading-relaxed">Enter your email address. We'll send you a one-time verification code to reset your password.</p>
    </div>

    <form action="{{ route('forgotPassword') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Email Input -->
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-medium text-gray-700">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fas fa-envelope text-sm"></i>
                </div>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="jane.doe@gmail.com"
                    required
                    value="{{ old('email') }}"
                    class="w-full pl-9 pr-3 py-2 bg-white border border-gray-400 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all"
                >
            </div>
            @error('email')
                <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
            @enderror
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2.5 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 mt-2 group">
            Send Verification Code
            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-0.5"></i>
        </button>
    </form>

    <div class="mt-6 text-center border-t border-gray-100 pt-6">
        <a href="{{ route('login') }}" class="text-xs text-gray-500 hover:text-purple-600 font-medium flex items-center justify-center gap-1.5 mx-auto transition-colors">
            <i class="fas fa-arrow-left text-[10px]"></i>
            Back to Log in
        </a>
    </div>
</div>

@endsection
