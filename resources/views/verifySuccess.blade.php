@extends('layouts.app')

@section('title', 'Verification Success')

@section('content')

<div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md items-center">
    <h1 class="text-3xl font-bold text-purple-600 mb-6 text-center ">Success</h1>

    <p class=="text-xl text-black text-center mt-4 mb-6">Email has been verified. You are all set. Go ahead and login!</p>

    <form action="{{ route('dashboard') }}" method="GET" class="flex items-center">
        @csrf

        <button type='submit' class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Login</button>

    </form>

</div>

@endsection
