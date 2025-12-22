@extends('layouts.app')

@section('title', 'Login')

@section('content')

        <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
            <h1 class="text-3xl font-bold text-purple-600 mb-6 text-center ">Login</h1>

            <form action="{{ route('login') }}" method="POST" class="">
                @csrf
                {{--mb for spacing between elements--}}
                <div class="mb-4">
                    <label for="email" class="text-gray-600 block">Email</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email" required value="{{ old('email') }}" class="border border-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 rounded-md w-full h-6 p-4">

                    @error('email')
                        <span class="text-red-400 text-sm">{{$message}}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="text-gray-600  text-bold block">Password</label>
                    <input type="password" id="password" minlength=8 required autocomplete="current-password" name="password" class="border border-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300 rounded-md w-full h-6 p-4">

                    @error('password')
                        <span class="text-red-500 text-sm">{{$message}}</span>
                    @enderror
                </div>

                <div class='mb-4'>
                    <input type='checkbox' id="remember" name="remember" class="rounded border-gray-200">
                    <label for='remember'>Remember me</label>
                </div>

                <div class="mb-4 flex items-center">
                    <button type="submit" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-full text-center h-8 shadow">Login</button>
                </div>

            </form>

            <div class="flex justify-between">
                <a href="{{ route('forgotPassword')}}">Forgot Password?</a>

               <p>Click <a href='{{ route( 'register.form' )}}' class="text-blue-500 underline">here</a> to register</p>
            </div>


        </div>

@endsection
