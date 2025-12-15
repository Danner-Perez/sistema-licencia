<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SIREM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layouts.sidebar')

    <div class="flex-1">
        @include('layouts.navbar')

        <main class="p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
