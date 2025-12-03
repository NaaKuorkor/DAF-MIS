<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>@yield('title', 'DAF web app')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" >
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/856b94abea.js" crossorigin="anonymous"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>
    <body>
        <x-d-header />

        <div class='flex p-2 gap-4  h-screen'>
            <!--Side bar-->
            <x-sidebar />
            <!--//Main Content-->
            <div class=' w-3/4 h-auto rounded-lg flex flex-1 border border-solid border-black flex-col shadow-lg' id='dashboardContent'>

            </div>
        </div>


    </body>
</html>
