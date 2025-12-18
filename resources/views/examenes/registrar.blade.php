@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white fw-bold">
            📝 Registrar Resultado de Examen
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

            <form method="POST" action="{{ route('examenes.store') }}">
                @csrf

                {{-- BUSCAR DNI --}}
                <div class="mb-4 position-relative">
                    <label class="form-label fw-bold">Buscar Postulante (DNI)</label>
                    <input type="text"
                           id="buscar-dni"
                           class="form-control form-control-lg"
                           placeholder="🔍 Ingrese DNI"
                           autocomplete="off">

                    <input type="hidden" name="id_postulante" id="postulante_id">
                    <input type="hidden" name="proceso_licencia_id" id="proceso_id">

                    <div id="lista-resultados"
                         class="list-group position-absolute w-100 shadow"></div>

                    <small class="text-danger" id="error"></small>
                </div>

                {{-- DATOS DEL POSTULANTE --}}
                <div class="card mb-4 border">
                    <div class="card-header fw-bold">
                        👤 Datos del Postulante
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">DNI</label>
                                <input type="text" id="dni"
                                       class="form-control" disabled>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nombre y Apellidos</label>
                                <input type="text" id="nombre"
                                       class="form-control" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Categoría Licencia</label>
                                <input type="text" id="tipo_licencia"
                                       class="form-control" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Placa</label>
                                <input type="text" id="placa"
                                       class="form-control" disabled>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- RESULTADO --}}
                <div class="card mb-4 border">
                    <div class="card-header fw-bold">
                        📊 Resultado del Examen
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Resultado</label>
                            <select name="resultado"
                                    class="form-select form-select-lg"
                                    required>
                                <option value="">Seleccione</option>
                                <option value="APROBADO">✅ APROBADO</option>
                                <option value="NO APROBADO">❌ NO APROBADO</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observación</label>
                            <textarea name="observacion"
                                      class="form-control"
                                      rows="2"
                                      placeholder="Observaciones (opcional)"></textarea>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-success btn-lg">
                        💾 Registrar Resultado
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const input = document.getElementById('buscar-dni');
    const lista = document.getElementById('lista-resultados');
    const error = document.getElementById('error');

    input.addEventListener('keyup', () => {
        const dni = input.value.trim();
        lista.innerHTML = '';
        error.textContent = '';

        if (dni.length < 3) return;

        axios.get('{{ route("examenes.buscar") }}', {
            params: { query: dni }
        })
        .then(res => {

            if (res.data.length === 0) {
                error.textContent = 'No se encontró postulante activo';
                return;
            }

            res.data.forEach(p => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'list-group-item list-group-item-action';
                btn.textContent = `${p.dni} - ${p.nombre}`;

                btn.onclick = () => {
                    document.getElementById('postulante_id').value = p.id_postulante;
                    document.getElementById('proceso_id').value = p.proceso_id;
                    document.getElementById('dni').value = p.dni;
                    document.getElementById('nombre').value = p.nombre;
                    document.getElementById('tipo_licencia').value = p.tipo_licencia;
                    document.getElementById('placa').value = p.placa ?? '—';
                    lista.innerHTML = '';
                };

                lista.appendChild(btn);
            });
        })
        .catch(() => {
            error.textContent = 'Error en la búsqueda';
        });
    });

    document.addEventListener('click', e => {
        if (!lista.contains(e.target) && e.target !== input) {
            lista.innerHTML = '';
        }
    });
});
</script>
@endpush
