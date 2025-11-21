<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'DAF web app')</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class='min-h-screen flex items-center justify-center bg-purple-200'>

        @yield('content')

    </body>
</html>
