<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Licencias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-950 text-gray-100" x-data="{ loginOpen: false }">

    {{-- NAVBAR --}}
    <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold tracking-wide">
            Sistema Licencias
        </h1>

        <button
            @click="loginOpen = true"
            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">
            Iniciar sesión
        </button>
    </nav>

    {{-- HERO --}}
    <section class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight">
                Plataforma integral para la
                <span class="text-blue-500">gestión de licencias</span>
            </h2>

            <p class="mt-6 text-gray-400 text-lg">
                Administra postulantes, asistencias, verificaciones y exámenes
                desde un solo sistema seguro y eficiente.
            </p>

            <div class="mt-8 flex gap-4">
                <button
                    @click="loginOpen = true"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">
                    Acceder al sistema
                </button>

               
            </div>
        </div>

        {{-- MOCKUP --}}
        <div class="hidden md:block">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-1 shadow-2xl">
                <div class="bg-gray-900 rounded-xl p-6">
                    <p class="text-sm text-gray-400 mb-2">Dashboard preview</p>
                    <div class="h-48 bg-gray-800 rounded-lg"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
            
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="text-center py-6 text-gray-500 text-sm">
        © {{ date('Y') }} Sistema de Licencias —
        Desarrollado por <span class="text-gray-300 font-semibold">DNR & SIGMA</span>
    </footer>

    {{-- MODAL LOGIN --}}
    {{-- MODAL LOGIN DARK SaaS --}}
<div
    x-show="loginOpen"
    x-transition
    class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50"
    @keydown.escape.window="loginOpen = false">

    <div
        @click.outside="loginOpen = false"
        class="bg-gray-900 text-gray-100 w-full max-w-md rounded-2xl shadow-2xl border border-gray-800 p-8">

        {{-- HEADER --}}
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold tracking-wide">
                Acceso al sistema
            </h3>
            <p class="text-sm text-gray-400 mt-1">
                Ingresa tus credenciales
            </p>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm text-gray-400 mb-1">
                    Correo electrónico
                </label>
                <input
                    type="email"
                    name="email"
                    required
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">
                    Contraseña
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:border-blue-500 transition">
            </div>

            <button
                type="submit"
                class="w-full py-2.5 rounded-lg font-semibold
                       bg-gradient-to-r from-blue-600 to-indigo-600
                       hover:from-blue-700 hover:to-indigo-700
                       transition shadow-lg">
                Ingresar
            </button>

            @if (Route::has('password.request'))
                <div class="text-center">
                    
                </div>
            @endif
        </form>
    </div>
</div>


</body>
</html>
