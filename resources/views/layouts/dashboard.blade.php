<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'DAF web app')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/856b94abea.js" crossorigin="anonymous"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>
    <body class='bg-gray-300'>
        <!--First wrap page for side navbar-->
        <div x-data="{navbarOpen:false, profileOpen:false}">

            <!--Header-->
            <div class="fixed top-0 left-0 w-full z-50">
                <!--First white bar-->
                <div class="bg-gray-200 h-10 px-6 flex items-center justify-center relative">
                    <button @click="navbarOpen = !navbarOpen" class="absolute left-6 text-2xl text-gray-700">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <img src="{{ asset('images/DAFlogo.jpg') }}"
                             alt="Logo"
                             class="h-8 w-auto object-contain mx-auto">

                </div>

                <!--Second purple bar-->
                <div class="bg-purple-400 flex items-center justify-between shadow px-6 h-10">
                    <h1 class="text-2xl font-bold text-white">Dashboard</h1>

                    <div x-data="{ open:false }" class="relative">

                        <button @click="open = !open" class="rounded-full w-8 h-8 bg-white hover:bg-gray-200 transition">
                            <i class="fa-solid fa-user"></i>
                        </button>

                        <div x-show="open" x-transition @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg hover:bg-gray-100 shadow-lg py-2 z-50">

                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Account</a>
                            <form method="POST" action="{{ route('logout', ['guard' => 'student']) }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-gray-800">
                                Logout
                            </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>


            <div x-show="navbarOpen"
                @click.away="navbarOpen = false"
                class="fixed top-0 left-0 h-full w-64 bg-gray-400 text-gray-700 z-60"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
            >
                <div class="p-6">
                    <nav class="space-y-4">
                        <button class="w-full text-left block hover:bg-gray-300 px-4 py-2">Dashboard</button>
                        <button  class="w-full text-left block hover:bg-gray-300 px-4 py-2">Announcements</button>
                        <button  class="w-full text-left block hover:bg-gray-300 px-4 py-2">Courses</button>
                    </nav>
                </div>

            </div>

            <!--Dim background when navbar is open-->
            <div x-show="navbarOpen" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 z-30" @click="navbarOpen = false"></div>

            @yield('content')

        </div>

    </body>
</html>
