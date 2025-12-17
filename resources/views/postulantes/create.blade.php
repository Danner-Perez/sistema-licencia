@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">➕ Registro de Postulante</h5>
        </div>

        <div class="card-body">

            {{-- ERRORES --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('postulantes.store') }}">
                @csrf

                {{-- DNI --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">DNI</label>
                    <input type="text"
                           name="dni"
                           maxlength="8"
                           value="{{ old('dni') }}"
                           class="form-control"
                           required>
                </div>

                {{-- NOMBRES --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombres</label>
                    <input type="text"
                           name="nombres"
                           value="{{ old('nombres') }}"
                           class="form-control"
                           required>
                </div>

                {{-- APELLIDOS --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Apellidos</label>
                    <input type="text"
                           name="apellidos"
                           value="{{ old('apellidos') }}"
                           class="form-control"
                           required>
                </div>

                <hr>

                {{-- TIPO LICENCIA --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipo de Licencia</label>
                    <select name="tipo_licencia"
                            class="form-select"
                            required>
                        <option value="">-- Seleccione --</option>
                        <option value="A1"  @selected(old('tipo_licencia')=='A1')>A1</option>
                        <option value="A2A" @selected(old('tipo_licencia')=='A2A')>A2A</option>
                        <option value="A2B" @selected(old('tipo_licencia')=='A2B')>A2B</option>
                        <option value="A3"  @selected(old('tipo_licencia')=='A3')>A3</option>
                    </select>
                </div>

                {{-- FECHA PSICOSOMÁTICO --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Fecha Psicosomático
                    </label>
                    <input type="date"
                           name="fecha_psicosomatico"
                           value="{{ old('fecha_psicosomatico') }}"
                           class="form-control"
                           required>
                    <small class="text-muted">
                        Vigencia: 6 meses
                    </small>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('postulantes.index') }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button class="btn btn-success">
                        💾 Guardar Postulante
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
