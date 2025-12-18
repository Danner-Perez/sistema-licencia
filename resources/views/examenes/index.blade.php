@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">📋 Exámenes del día</h4>

        <a href="{{ route('examenes.create') }}"
           class="btn btn-primary">
            ➕ Registrar Examen
        </a>
        <a href="{{ route('examenes.exportarHoy') }}" class="btn btn-success mb-3">
            📥 Exportar Exámenes de Hoy
        </a>

    </div>

    {{-- MENSAJES --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body p-0">

            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>DNI</th>
                        <th>Postulante</th>
                        <th>Licencia</th>
                        <th>Placa</th>
                        <th>Resultado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($postulantes as $i => $p)
                        @php
                            $examen = $p->examenes->first();
                            $verificacion = $p->verificaciones->first();
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->dni }}</td>
                            <td>{{ $p->nombres }} {{ $p->apellidos }}</td>

                            <td>
                                {{ optional($p->procesoActivo)->tipo_licencia ?? '—' }}
                            </td>

                            <td>
                                {{ $verificacion?->placa ?? '—' }}
                            </td>

                            <td>
                                @if ($examen?->resultado === 'APROBADO')
                                    <span class="badge bg-success">APROBADO</span>
                                @elseif ($examen?->resultado === 'NO APROBADO')
                                    <span class="badge bg-danger">NO APROBADO</span>
                                @else
                                    <span class="badge bg-secondary">SIN EXAMEN</span>
                                @endif
                            </td>

                            <td>
                                {{ $examen?->fecha?->format('d/m/Y') ?? '—' }}
                            </td>
                        </tr>
                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-3">
                                No hay exámenes registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
