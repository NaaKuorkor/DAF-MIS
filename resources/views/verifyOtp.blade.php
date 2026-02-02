@extends('layouts.auth-split')

@section('title', 'Verify OTP')

@section('heading', 'Verify Identity')
@section('subheading')
    We've sent a 6-digit verification code to <span class="font-bold text-gray-900">{{ $maskedEmail ?? 'your email' }}</span>
@endsection

@section('content')
    <form action="{{ route('verifyOTP') }}" method="POST" id="otpForm" class="space-y-8">
        @csrf

        <!-- Hidden input to collect full OTP -->
        <input type="hidden" name="otp" id="otp-value">

        <!-- OTP Inputs -->
        <div class="flex justify-between gap-2" id="otp-container">
            <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0" autofocus>
            <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
            <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
            <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
            <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
            <input type="number" maxlength="1" class="otp-input w-12 h-14 border-2 border-gray-300 rounded-xl text-center text-xl font-bold text-gray-800 focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 outline-none transition-all bg-gray-50 focus:bg-white placeholder-transparent" placeholder="0">
        </div>

        @error('otp')
            <div class="text-red-500 text-sm text-center font-medium">{{ $message }}</div>
        @enderror

        <!-- Timer & Resend -->
        <div class="flex flex-col items-center justify-center gap-3">
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

        <!-- Back Link -->
        <div class="pt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-purple-600 flex items-center justify-center gap-2 transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i>
                Back to Login
            </a>
        </div>
    </form>

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

