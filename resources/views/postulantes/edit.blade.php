@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">✏️ Editar Postulante</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('postulantes.update', $postulante->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control"
                   value="{{ old('dni', $postulante->dni) }}" maxlength="8" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombres</label>
            <input type="text" name="nombres" class="form-control"
                   value="{{ old('nombres', $postulante->nombres) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control"
                   value="{{ old('apellidos', $postulante->apellidos) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de Licencia</label>
            <select name="tipo_licencia" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="A1" {{ $postulante->tipo_licencia == 'A1' ? 'selected' : '' }}>A1</option>
                <option value="A2A" {{ $postulante->tipo_licencia == 'A2A' ? 'selected' : '' }}>A2A</option>
                <option value="A2B" {{ $postulante->tipo_licencia == 'A2B' ? 'selected' : '' }}>A2B</option>
                <option value="A3A" {{ $postulante->tipo_licencia == 'A3A' ? 'selected' : '' }}>A3A</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Examen</label>
            <input type="date" name="fecha_examen" class="form-control"
                   value="{{ old('fecha_examen', $postulante->fecha_examen) }}" required>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">💾 Actualizar</button>
            <a href="{{ route('postulantes.index') }}" class="btn btn-secondary">↩ Volver</a>
        </div>
    </form>
</div>
@endsection
