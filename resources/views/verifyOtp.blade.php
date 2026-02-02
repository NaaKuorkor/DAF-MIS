@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')

<div class="max-w-md w-full bg-white rounded-2xl shadow-md overflow-hidden border border-purple-200 relative">

    <!-- Decorative Top Bar -->
    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-600 to-purple-800"></div>

    <div class="p-8 pt-10">
        <!-- Icon -->
        <div class="mx-auto w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mb-6 ring-4 ring-purple-50/50">
            <i class="fas fa-shield-alt text-3xl text-purple-600"></i>
        </div>

        <!-- Text Content -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Verify it's you</h1>
            <p class="text-gray-500 text-sm leading-relaxed">
                We've sent a 6-digit verification code to<br>
                <span class="font-medium text-gray-900">{{ $maskedEmail ?? 'your email' }}</span>
            </p>
        </div>

        <!-- OTP Form -->
        <form action="{{ route('verifyOTP') }}" method="POST" id="otpForm">
            @csrf

            <!-- Hidden input to collect full OTP -->
            <input type="hidden" name="otp" id="otp-value">

            <!-- Input Group -->
            <div class="flex justify-between gap-2 mb-8" id="otp-container">
                <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0" autofocus>
                <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
                <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
                <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
                <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
                <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
            </div>

            @error('otp')
                <div class="text-red-500 text-sm text-center mb-4">{{ $message }}</div>
            @enderror

            <!-- Timer Section -->
            <div class="flex flex-col items-center justify-center mb-8 gap-2">
                <div class="text-sm text-gray-500 font-medium flex items-center gap-2">
                    <i class="far fa-clock"></i>
                    <span id="timer">00:59</span>
                </div>
                <button type="button" id="resendBtn" class="text-sm text-purple-600 font-semibold hover:text-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled onclick="resetTimer()">
                    Resend Code
                </button>
            </div>

            <!-- Verify Button -->
            <button type="submit" id="verifyBtn" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-purple-600/20 active:scale-[0.98] flex items-center justify-center gap-2 group">
                <span>Verify Identity</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-purple-600 flex items-center justify-center gap-2 transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i>
                Back to Login
            </a>
        </div>
    </div>
</div>

<style>
    /* Remove spinner from number input */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    /* Animation for shake on error */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
        20%, 40%, 60%, 80% { transform: translateX(4px); }
    }
    .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
</style>

@vite(['resources/js/verifyOtp.js'])
@endsection
