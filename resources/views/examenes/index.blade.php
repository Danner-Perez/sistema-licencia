@extends('layouts.app')

@section('content')
<div class="card shadow">
    <div class="card-header fw-bold">
        📝 Registro de Examen
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('examenes.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Postulante</label>
                <select name="proceso_licencia_id" class="form-select" required>
                    <option value="">Seleccione</option>
                    @foreach($procesos as $proceso)
                        <option value="{{ $proceso->id }}">
                            {{ $proceso->postulante->dni }} - 
                            {{ $proceso->postulante->nombres }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Resultado</label>
                <select name="resultado" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="APROBADO">APROBADO</option>
                    <option value="DESAPROBADO">DESAPROBADO</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Observación</label>
                <textarea name="observacion" class="form-control"></textarea>
            </div>

            <button class="btn btn-success">
                💾 Registrar intento
            </button>
        </form>

    </div>
</div>
@endsection
