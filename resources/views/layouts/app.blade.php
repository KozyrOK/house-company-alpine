<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Housing Company') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow p-4">
            <div class="container mx-auto">
                <h1 class="text-lg font-bold">Housing Company</h1>
            </div>
        </header>
        <main class="flex-1 container mx-auto p-6">
            @yield('content')
        </main>
        <footer class="bg-gray-200 text-center p-4 text-sm">
            © {{ date('Y') }} Housing Company
        </footer>
    </div>
    </body>
</html>
