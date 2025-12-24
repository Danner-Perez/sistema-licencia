<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Sistema de Licencias') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
      rel="stylesheet" />


    {{-- VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 text-gray-800 antialiased">

    {{-- NAVBAR --}}
    @include('layouts.navigation')

    {{-- CONTENIDO --}}
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="mt-10 border-t border-gray-200 py-4 text-center text-sm text-gray-500">
        © {{ date('Y') }} Sistema de Licencias · Desarrollado por <strong>DNR & SIGMA</strong>
    </footer>

    @stack('scripts')
</body>
</html>
