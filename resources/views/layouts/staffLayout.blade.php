<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
         <title>@yield('title', 'DAF web app')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/856b94abea.js" crossorigin="anonymous"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>
    <body>
        <x-d-header />

        <div class='flex p-2 gap-4  h-screen'>
            <!--Side bar-->
            <div class="w-12 h-auto flex flex-col border border-solid border-black rounded-lg shadow-lg  p-4">
                <form method="POST" action="#">
                    @csrf
                    <button type='submit' class="w-50 bg-gray-200 rounded-lg m-5 p-4">Overview</button>
                </form>
            </div>

            <!--//Main Content-->
            <div class='w-3/4 h-auto rounded-lg flex flex-1 border border-solid border-black flex-col shadow-lg '>

            </div>
        </div>
    </body>
</html>
