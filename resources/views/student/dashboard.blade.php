<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'DAF web app')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/856b94abea.js" crossorigin="anonymous"></script>
    </head>
    <body class='bg-grey-200'>


        <div class="fixed top-0 left-0 w-full z-50">

            <div class="bg-white h-10 px-6 flex items-center justify-between">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="bg-purple-600 flex items-center justify-between h-10 px-4">
                <h1 class="text-2xl font-bold text-white  ">Student Dashboard</h1>
                <form method="POST" action="{{ route('logout', ['guard' => 'student']) }}">
                    @csrf
                    <button type="submit" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-semibold">
                        Logout
                    </button>
                </form>

            </div>

        </div>

        <div class="pt-24 px-6 space-y-6">


            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <h2 class="text-2xl font-bold text-purple-600 mb-4">Announcements</h2>
                <p class="text-gray-500 text-center text-lg py-4">No current announcements</p>
            </div>


            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <h2 class="text-2xl font-bold text-purple-600 mb-4">Enrolled Courses</h2>

                <div class="text-center text-gray-500 text-lg py-8">
                    <div class="text-6xl mb-4"></div>
                    <p>No courses yet</p>
                </div>
            </div>
        </div>






    </body>
</html>
