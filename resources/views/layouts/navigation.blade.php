<nav class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16 items-center">

            {{-- LOGO --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold hover:text-gray-300">
                    Sistema Licencias
                </a>
            </div>

            @php
                // Menú por rol
                $menu = [
                    'admin' => [
                        ['name' => 'Dashboard', 'route' => 'dashboard'],
                        ['name' => 'Postulantes', 'route' => 'postulantes.index'],
                        ['name' => 'Asistencia', 'route' => 'asistencias.index'],
                        ['name' => 'Verificación', 'route' => 'verificaciones.index'],
                        ['name' => 'Exámenes', 'route' => 'examenes.index'],
                        ['name' => 'Usuarios', 'route' => 'admin.users.index'], // Solo admin
                    ],
                    'examinador' => [
                        ['name' => 'Postulantes', 'route' => 'postulantes.index'],
                        ['name' => 'Verificación', 'route' => 'verificaciones.index'],
                        ['name' => 'Exámenes', 'route' => 'examenes.index'],
                    ],
                    'asistencia' => [
                        ['name' => 'Asistencia', 'route' => 'asistencias.index'],
                    ],
                ];

                $rolActual = auth()->user()->rol;
            @endphp

            {{-- MENÚ DESKTOP --}}
            <div class="hidden md:flex md:items-center md:space-x-6">
                @foreach($menu[$rolActual] ?? [] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-3 py-2 rounded-md text-sm font-medium
                       {{ request()->routeIs($item['route']) ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </div>

            {{-- USUARIO DESKTOP --}}
            <div class="hidden md:flex items-center" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-800 hover:bg-gray-700">
                    <span>{{ Auth::user()->name }}</span>
                    <span class="text-xs bg-gray-600 px-2 py-0.5 rounded">{{ $rolActual }}</span>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute right-4 top-16 w-40 bg-white text-gray-800 rounded shadow-lg overflow-hidden z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Perfil</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            {{-- MENÚ MOBILE --}}
            <div class="flex md:hidden items-center" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-300 hover:text-white focus:outline-none">☰</button>

                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute top-16 left-0 w-full bg-gray-900 border-t border-gray-700 z-50">
                    <div class="px-4 py-3 space-y-2">
                        @foreach($menu[$rolActual] ?? [] as $item)
                            <a href="{{ route($item['route']) }}"
                               class="block px-3 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white">
                                {{ $item['name'] }}
                            </a>
                        @endforeach

                        <hr class="border-gray-700">

                        <a href="{{ route('profile.edit') }}"
                           class="block px-3 py-2 text-gray-300 hover:bg-gray-700">Perfil</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-3 py-2 text-red-400 hover:bg-gray-700">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>
