@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">✏️ Editar Postulante</h5>
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

            <form method="POST"
                  action="{{ route('postulantes.update', $postulante) }}">
                @csrf
                @method('PUT')

                {{-- DNI --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">DNI</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $postulante->dni }}"
                           disabled>
                </div>

                {{-- NOMBRES --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombres</label>
                    <input type="text"
                           name="nombres"
                           value="{{ old('nombres', $postulante->nombres) }}"
                           class="form-control"
                           required>
                </div>

                {{-- APELLIDOS --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Apellidos</label>
                    <input type="text"
                           name="apellidos"
                           value="{{ old('apellidos', $postulante->apellidos) }}"
                           class="form-control"
                           required>
                </div>
                                {{-- TIPO LICENCIA --}}
                @if($postulante->procesoActivo)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo de Licencia</label>
                        <select name="tipo_licencia" class="form-select" required>
                            @foreach(['A1','A2A','A2B','A3'] as $tipo)
                                <option value="{{ $tipo }}"
                                    @selected($postulante->procesoActivo->tipo_licencia === $tipo)>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif


                {{-- FECHA PSICOSOMÁTICO --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Fecha Psicosomático
                    </label>
                    <input type="date"
                           name="fecha_psicosomatico"
                           value="{{ old('fecha_psicosomatico', $postulante->fecha_psicosomatico?->format('Y-m-d')) }}"
                           class="form-control"
                           required>
                </div>

                {{-- VALIDACIÓN RENIEC --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Validación RENIEC</label>
                    <select name="validado_reniec" class="form-select">
                        <option value="0" @selected(!$postulante->validado_reniec)>
                            ❌ No validado
                        </option>
                        <option value="1" @selected($postulante->validado_reniec)>
                            ✅ Validado
                        </option>
                    </select>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('postulantes.index') }}"
                       class="btn btn-secondary">
                        Volver
                    </a>

                    <button class="btn btn-warning">
                        💾 Guardar Cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
