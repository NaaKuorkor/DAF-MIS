@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

        <div class="pt-24 px-6 space-y-6">


            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <h2 class="text-2xl font-bold text-purple-600 mb-4">Announcements</h2>
                <p class="text-gray-500 text-center text-lg py-4">No current announcements</p>
            </div>


            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <h2 class="text-2xl font-bold text-purple-600 mb-4">Enrolled Courses</h2>

                <div class="text-center text-gray-500 text-lg py-8">
                    <div class="flex flex-wrap">
                        <x-card title="Life Skills" description="Essential skills to make headways in life."/>
                    </div>
                </div>
            </div>
        </div>

@endsection




