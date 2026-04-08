<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tadieu</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('styles')
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        @yield('content')
    </body>

    @yield('scripts')
</html>