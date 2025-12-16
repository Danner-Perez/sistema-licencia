@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4 fw-bold">✏️ Editar Postulante</h4>

    <form method="POST" action="{{ route('postulantes.update', $postulante) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni"
                   value="{{ old('dni', $postulante->dni) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombres</label>
            <input type="text" name="nombres"
                   value="{{ old('nombres', $postulante->nombres) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos"
                   value="{{ old('apellidos', $postulante->apellidos) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo Licencia</label>
            <select name="tipo_licencia" class="form-select">
                @foreach(['A1','A2A','A2B','A3'] as $lic)
                    <option value="{{ $lic }}"
                        @selected($postulante->tipo_licencia === $lic)>
                        {{ $lic }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha Psicofísico</label>
            <input type="date"
                name="fecha_psicofisico"
                value="{{ old('fecha_psicofisico', $postulante->fecha_psicofisico) }}"
                class="form-control">
        </div>


        <button class="btn btn-primary">
            💾 Actualizar
        </button>

        <a href="{{ route('postulantes.index') }}" class="btn btn-secondary">
            ⬅️ Volver
        </a>

    </form>
</div>
@endsection
