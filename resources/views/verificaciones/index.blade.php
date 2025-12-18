<!-- resources/views/verificaciones/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">📋 Verificaciones</h2>

    <a href="{{ route('verificaciones.create') }}" class="btn btn-primary mb-3">
        ➕ Nueva Verificación
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Placa</th>
                <!--<th>Tipo Vehículo</th>
                <th>Marca / Modelo</th>--->
                <th>Fecha</th> 
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {{-- Aquí recorrerías tus verificaciones --}}
            @forelse($verificaciones as $v)
            <tr>
                <td>{{ $v->postulante->dni }}</td>
                <td>{{ $v->postulante->nombres }} {{ $v->postulante->apellidos }}</td>
                <td>{{ $v->placa }}</td>
                <!--<td>{{ $v->tipo_vehiculo ?? '—' }}</td>
                    <td>{{ $v->marca ?? '—' }} / {{ $v->modelo ?? '—' }}</td>-->
                <td>{{ $v->fecha->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('verificaciones.edit', $v) }}" class="btn btn-sm btn-warning">✏️</a>
                    <form action="{{ route('verificaciones.destroy', $v) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No hay verificaciones registradas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection