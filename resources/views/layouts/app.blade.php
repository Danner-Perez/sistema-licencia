<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Sistema de Licencias') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- VITE (Tailwind + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100 text-gray-800 min-h-screen">

    {{-- NAVEGACIÓN --}}
    @include('layouts.navigation')

    {{-- CONTENIDO --}}
    <main class="max-w-7xl mx-auto px-4 py-6">

        {{-- MENSAJES --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')

</body>
</html>
