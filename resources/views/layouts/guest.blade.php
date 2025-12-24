<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Sistema de Licencias') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">

        {{-- LOGO --}}
        <div class="flex justify-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600
                        flex items-center justify-center text-white font-bold text-xl">
                SL
            </div>
        </div>

        {{ $slot }}

        <p class="mt-6 text-center text-xs text-gray-400">
            © {{ date('Y') }} DNR & SIGMA
        </p>
    </div>

</body>
</html>
