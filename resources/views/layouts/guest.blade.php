<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Sistema de Licencias') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
        {{ $slot }}
    </div>

</body>
</html>
