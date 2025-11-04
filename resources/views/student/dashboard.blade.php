@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<header class="fixed top-0 left-0 shadow-md bg-white flex justify between z-50 p-4 h-20 w-full">
    <form method="POST" action="{{ route('logout', ['guard' => 'student']) }}" class="flex justify-end ">
        @csrf
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold h-10 w-40 text-center">Logout</button>

    </form>

</header>


@endsection

