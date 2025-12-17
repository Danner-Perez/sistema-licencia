@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- TÍTULO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">👥 Gestión de Postulantes</h4>
        <a href="{{ route('postulantes.create') }}" class="btn btn-primary">
            ➕ Nuevo Postulante
        </a>
    </div>

    {{-- BUSCADOR --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="buscar"
                   class="form-control"
                   placeholder="🔍 Buscar por DNI o nombre">
        </div>

        <div class="col-md-3">
            <form method="GET" class="d-flex gap-2">
                <input type="date" name="fecha"
                    value="{{ request('fecha', now()->toDateString()) }}"
                    class="form-control">
                <button class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>DNI</th>
                        <th>Postulante</th>
                        <th>Licencia</th>
                        <th>Psicosomático</th>
                        <th>Vence</th>
                        <th>Días</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tabla">
                @forelse ($postulantes as $p)
                    @php
                        $proceso = $p->procesoActivo;
                        $vence = $p->fechaVencimientoPsicosomatico();
                        $dias = $p->diasRestantesPsicosomatico();
                        $estado = $dias === null ? 'SIN REGISTRO' :
                                  ($dias < 0 ? 'VENCIDO' : ($dias <= 7 ? 'POR VENCER' : 'VIGENTE'));
                    @endphp

                    <tr>
                        <td class="fw-bold">{{ $p->dni }}</td>

                        <td>
                            {{ $p->nombres }}<br>
                            <small class="text-muted">{{ $p->apellidos }}</small>
                        </td>

                        {{-- TIPO LICENCIA --}}
                        <td>{{ $proceso->tipo_licencia ?? '—' }}</td>

                        {{-- FECHA PSICOSOMÁTICO --}}
                        <td>{{ $p->fecha_psicosomatico?->format('d/m/Y') ?? '—' }}</td>

                        {{-- FECHA VENCIMIENTO --}}
                        <td>{{ $vence?->format('d/m/Y') ?? '—' }}</td>

                        {{-- DÍAS RESTANTES --}}
                        <td>
                            @if($dias !== null)
                                <span class="fw-bold {{ $dias <= 7 ? 'text-danger' : 'text-success' }}">
                                    {{ $dias }}
                                </span>
                            @else
                                —
                            @endif
                        </td>

                        {{-- ESTADO --}}
                        <td>
                            @switch($estado)
                                @case('VIGENTE')
                                    <span class="badge bg-success">Vigente</span>
                                    @break
                                @case('POR VENCER')
                                    <span class="badge bg-warning text-dark">Por vencer</span>
                                    @break
                                @case('VENCIDO')
                                    <span class="badge bg-danger">Vencido</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Sin registro</span>
                            @endswitch
                        </td>

                        {{-- ACCIONES --}}
                        <td class="text-center">
                            <a href="{{ route('postulantes.edit', $p) }}"
                               class="btn btn-sm btn-outline-primary">
                                ✏️
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
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

@push('scripts')
<script>
document.getElementById('buscar').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#tabla tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';
    });
});
</script>
@endpush
