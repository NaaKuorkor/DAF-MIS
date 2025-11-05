@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')

<div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
    <h1 class="text-3xl font-bold text-purple-600 mb-6 text-center ">Email verification</h1>

    <p class=="text-xl text-black text-center mt-4 mb-6">Before continuing, please check your email for a verification link.</p>

    <form action="" method="POST" class="">
        @csrf

        <button type='submit'>Resend verification email</button>

    </form>

    <div class="flex items-center">
        <a href="#">Resend notification</a>

       <p>Click <a href='#'>here</a> to register</p>
    </div>


</div>

@endsection
