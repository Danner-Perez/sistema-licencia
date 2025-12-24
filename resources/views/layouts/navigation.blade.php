<nav
    x-data="{ mobileOpen: false, userOpen: false }"
    class="sticky top-0 z-50 bg-gray-900 border-b border-gray-800">

    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">

            {{-- LOGO --}}
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600
                            flex items-center justify-center font-bold text-white">
                    SL
                </div>
                <a href="{{ route('dashboard') }}"
                   class="text-lg font-semibold text-white tracking-wide">
                    Sistema Licencias
                </a>
            </div>

            @php
                $menu = [
                    'admin' => [
                        ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard'],
                        ['name' => 'Postulantes', 'route' => 'postulantes.index', 'icon' => 'groups'],
                        ['name' => 'Asistencia', 'route' => 'asistencias.index', 'icon' => 'how_to_reg'],
                        ['name' => 'Verificación', 'route' => 'verificaciones.index', 'icon' => 'fact_check'],
                        ['name' => 'Exámenes', 'route' => 'examenes.index', 'icon' => 'quiz'],
                        ['name' => 'Usuarios', 'route' => 'admin.users.index', 'icon' => 'manage_accounts'],
                    ],
                    'examinador' => [
                        ['name' => 'Postulantes', 'route' => 'postulantes.index', 'icon' => 'groups'],
                        ['name' => 'Verificación', 'route' => 'verificaciones.index', 'icon' => 'fact_check'],
                        ['name' => 'Exámenes', 'route' => 'examenes.index', 'icon' => 'quiz'],
                    ],
                    'asistencia' => [
                        ['name' => 'Asistencia', 'route' => 'asistencias.index', 'icon' => 'how_to_reg'],
                    ],
                ];

                $rolActual = auth()->user()->rol;
            @endphp

            {{-- MENU DESKTOP --}}
            <div class="hidden md:flex items-center gap-1">
                @foreach($menu[$rolActual] ?? [] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition
                       {{ request()->routeIs($item['route'])
                           ? 'bg-gray-800 text-white'
                           : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="material-symbols-outlined text-base">
                            {{ $item['icon'] }}
                        </span>
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </div>

            {{-- USUARIO DESKTOP --}}
            <div class="hidden md:flex items-center relative">
                <button @click="userOpen = !userOpen"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800">

                    <div class="w-9 h-9 rounded-full bg-indigo-600
                                flex items-center justify-center font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="text-left">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst($rolActual) }}</p>
                    </div>
                </button>

                <div x-show="userOpen"
                     @click.outside="userOpen = false"
                     x-transition
                     class="absolute right-0 top-14 w-48 bg-gray-800
                            border border-gray-700 rounded-xl shadow-xl">

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                        <span class="material-symbols-outlined text-sm">person</span>
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex items-center gap-2 w-full px-4 py-3
                                       text-red-400 hover:bg-gray-700">
                            <span class="material-symbols-outlined text-sm">logout</span>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            {{-- BOTÓN HAMBURGUESA (ANIMADO) --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden relative w-10 h-10 flex items-center justify-center">

                <span class="absolute h-0.5 w-6 bg-white transition-all duration-300"
                      :class="mobileOpen ? 'rotate-45' : '-translate-y-2'"></span>

                <span class="absolute h-0.5 w-6 bg-white transition-all duration-300"
                      :class="mobileOpen ? 'opacity-0' : ''"></span>

                <span class="absolute h-0.5 w-6 bg-white transition-all duration-300"
                      :class="mobileOpen ? '-rotate-45' : 'translate-y-2'"></span>
            </button>

        </div>
    </div>

    {{-- OVERLAY --}}
    <div x-show="mobileOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/70 z-40 md:hidden"
         @click="mobileOpen = false"></div>

    {{-- DRAWER MÓVIL --}}
    <aside x-show="mobileOpen"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed top-0 left-0 w-72 h-full bg-gray-950
                  border-r border-gray-800 z-50 md:hidden">

        <div class="px-4 py-4 border-b border-gray-800">
            <p class="font-semibold text-white">{{ Auth::user()->name }}</p>
            <p class="text-sm text-gray-400">{{ ucfirst($rolActual) }}</p>
        </div>

        <div class="px-2 py-3 space-y-1">
            @foreach($menu[$rolActual] ?? [] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg
                          text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span class="material-symbols-outlined">
                        {{ $item['icon'] }}
                    </span>
                    {{ $item['name'] }}
                </a>
            @endforeach
        </div>

        <div class="border-t border-gray-800 my-2"></div>

        <div class="px-2">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-800">
                <span class="material-symbols-outlined">person</span>
                Perfil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 w-full px-4 py-3 rounded-lg
                               text-red-400 hover:bg-gray-800">
                    <span class="material-symbols-outlined">logout</span>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>
</nav>
