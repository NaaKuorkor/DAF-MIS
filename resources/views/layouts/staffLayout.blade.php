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

    </body>
</html>
