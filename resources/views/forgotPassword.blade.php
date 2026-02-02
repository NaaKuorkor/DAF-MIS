@extends('layouts.auth-split')

@section('title', 'Forgot Password')

@section('heading', 'Reset Password')
@section('subheading', 'Enter your email address. We\'ll send you a one-time verification code to reset your password.')

@section('content')

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('forgotPassword') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Email Input -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
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
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm"
                >
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:-translate-y-0.5 group">
            Send Verification Code
            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
        </button>

        <div class="mt-6 text-center border-t border-gray-100 pt-6">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-purple-600 flex items-center justify-center gap-2 transition-colors group">
                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                Back to Log in
            </a>
        </div>
    </form>
@endsection

