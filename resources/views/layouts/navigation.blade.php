<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        {{-- LOGO / DASHBOARD --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Sistema Licencias
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            @php
                // Menu por rol
                $menu = [
                    'admin' => [
                        ['name' => 'Postulantes', 'route' => 'postulantes.index'],
                        ['name' => 'Asistencia', 'route' => 'asistencias.index'],
                        ['name' => 'Verificación', 'route' => 'verificaciones.index'],
                        ['name' => 'Exámenes', 'route' => 'examenes.index'],
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

            <ul class="navbar-nav me-auto">
                @foreach($menu[$rolActual] ?? [] as $item)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(str_replace('.index','*',$item['route'])) ? 'active' : '' }}"
                           href="{{ route($item['route']) }}">
                            {{ $item['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- USUARIO --}}
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }} 
                        <span class="badge bg-secondary">{{ $rolActual }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                Perfil
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>
