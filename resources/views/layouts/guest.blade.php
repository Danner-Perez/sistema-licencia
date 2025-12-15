<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="w-64 bg-gray-800 text-white min-h-screen">
            <h2 class="text-xl font-bold p-4">SIREM</h2>

            <ul class="space-y-2 px-4">
                <li><a href="{{ route('dashboard') }}" class="block hover:bg-gray-700 p-2 rounded">Dashboard</a></li>
                <li><a href="{{ route('postulantes.index') }}" class="block hover:bg-gray-700 p-2 rounded">Postulantes</a></li>
                <li><a href="{{ route('asistencias.marcar') }}" class="block hover:bg-gray-700 p-2 rounded">Asistencia</a></li>
                <li><a href="{{ route('verificaciones.registrar') }}" class="block hover:bg-gray-700 p-2 rounded">Verificación</a></li>
                <li><a href="{{ route('examenes.index') }}" class="block hover:bg-gray-700 p-2 rounded">Exámenes</a></li>
            </ul>
        </div>

    </body>
</html>
