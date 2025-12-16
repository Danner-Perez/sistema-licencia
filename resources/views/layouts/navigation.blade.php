<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            🚗 Sistema Licencias
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav me-auto">

                {{-- DASHBOARD (TODOS) --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                {{-- ADMIN (TODO) --}}
                @if(auth()->user()->rol === 'admin')

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('postulantes.index') }}">
                            Postulantes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('examenes.index') }}">
                            Exámenes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Asistencia
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Verificación
                        </a>
                    </li>

                {{-- EXAMINADOR --}}
                @elseif(auth()->user()->rol === 'examinador')

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('postulantes.index') }}">
                            Postulantes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('examenes.index') }}">
                            Exámenes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Verificación
                        </a>
                    </li>

                {{-- ASISTENCIA --}}
                @elseif(auth()->user()->rol === 'asistencia')

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Asistencia
                        </a>
                    </li>

                @endif

            </ul>

            {{-- USUARIO --}}
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }} ({{ Auth::user()->rol }})
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
