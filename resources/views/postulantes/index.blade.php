@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- TÍTULO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">
            👥 Gestión de Postulantes
        </h4>

        @if(in_array(auth()->user()->rol, ['admin','examinador']))
            <a href="{{ route('postulantes.create') }}" class="btn btn-primary">
                ➕ Nuevo Postulante
            </a>
        @endif
    </div>

    {{-- MENSAJE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABLA --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>DNI</th>
                        <th>Postulante</th>
                        <th>Licencia</th>
                        <th>Psicofísico</th>
                        <th>Días restantes</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($postulantes as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->dni }}</td>

                        <td>
                            {{ $p->nombres }} <br>
                            <small class="text-muted">{{ $p->apellidos }}</small>
                        </td>

                        <td>
                            <span class="badge bg-info">
                                {{ $p->tipo_licencia }}
                            </span>
                        </td>

                        {{-- FECHA PSICOFÍSICO --}}
                        <td>
                            @if($p->fecha_psicofisico)
                                {{ \Carbon\Carbon::parse($p->fecha_psicofisico)->format('d/m/Y') }}
                            @else
                                —
                            @endif

                        </td>

                        {{-- DÍAS RESTANTES --}}
                        <td>
                            @if ($p->dias_restantes_psicofisico !== null)
                                <span class="fw-bold
                                    {{ $p->dias_restantes_psicofisico <= 7 ? 'text-danger' : 'text-success' }}">
                                    {{ $p->dias_restantes_psicofisico }} días
                                </span>
                            @else
                                —
                            @endif
                        </td>


                        {{-- ESTADO --}}
                        <td>
                            @switch($p->estado_psicofisico)
                                @case('VIGENTE')
                                    <span class="badge bg-success">
                                        🟢 Vigente
                                    </span>
                                    @break

                                @case('POR VENCER')
                                    <span class="badge bg-warning text-dark">
                                        🟡 Por vencer
                                    </span>
                                    @break

                                @case('VENCIDO')
                                    <span class="badge bg-danger">
                                        🔴 Vencido
                                    </span>
                                    @break

                                @default
                                    <span class="badge bg-secondary">
                                        ⚪ Sin registro
                                    </span>
                            @endswitch
                        </td>

                        {{-- ACCIONES --}}
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">

                                <a href="{{ route('postulantes.edit', $p) }}"
                                    class="btn btn-outline-primary"
                                    title="Editar postulante">
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                @if(auth()->user()->rol === 'admin')
                                    <form method="POST"
                                          action="{{ route('postulantes.destroy', $p) }}"
                                          onsubmit="return confirm('¿Eliminar postulante?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger">
                                            🗑️
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay postulantes registrados
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>
</div>
@endsection
