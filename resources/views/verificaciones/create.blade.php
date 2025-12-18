@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Registrar Verificación</h2>
        <a href="{{ route('verificaciones.index') }}" class="btn btn-secondary">⬅ Volver</a>
    </div>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('verificaciones.store') }}" method="POST">
        @csrf

        {{-- BUSCADOR POSTULANTE --}}
        <div class="mb-3 position-relative">
            <label for="buscar-postulante" class="form-label">Buscar Postulante (DNI, nombres, apellidos)</label>
            <input type="text" id="buscar-postulante" class="form-control" placeholder="Escriba al menos 3 caracteres" autocomplete="off">
            <input type="hidden" name="id_postulante" id="postulante-id">
            <div id="lista-postulantes" class="list-group mt-1 position-absolute w-100"></div>
            <div id="error-busqueda" class="text-danger mt-1"></div>
        </div>

        {{-- PLACA --}}
        <div class="mb-3">
            <label for="placa" class="form-label">Placa</label>
            <input type="text" name="placa" id="placa" class="form-control" required>
        </div>

        {{-- BOTONES --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('verificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Verificación</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
console.log('SCRIPT CARGADO');

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('buscar-postulante');
    const lista = document.getElementById('lista-postulantes');
    const hidden = document.getElementById('postulante-id');
    const errorDiv = document.getElementById('error-busqueda');

    let timeout = null;

    input.addEventListener('keyup', function() {
        
        clearTimeout(timeout);
        const query = this.value.trim();
        lista.innerHTML = '';
        errorDiv.textContent = '';
        hidden.value = '';

        if (query.length < 3) {
            errorDiv.textContent = 'Ingrese al menos 3 caracteres para buscar.';
            return;
        }

        timeout = setTimeout(() => {
            axios.get("/verificaciones/buscar-postulante", { params: { query } })
                .then(res => {
                    const data = res.data;
                    lista.innerHTML = '';
                    if (data.length === 0) {
                        errorDiv.textContent = 'No se encontraron postulantes con esos datos.';
                        return;
                    }
                    data.forEach(postulante => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = `${postulante.nombres} ${postulante.apellidos} (DNI: ${postulante.dni})`;
                        item.addEventListener('click', () => {
                            input.value = `${postulante.nombres} ${postulante.apellidos} (DNI: ${postulante.dni})`;
                            hidden.value = postulante.id_postulante;
                            lista.innerHTML = '';
                            errorDiv.textContent = '';
                        });
                        lista.appendChild(item);
                    });
                })
                .catch(err => {
                    console.error(err);
                    errorDiv.textContent = 'Ocurrió un error al buscar postulantes.';
                });
        }, 300);
    });

    // Ocultar lista si se hace clic fuera
    document.addEventListener('click', function(e){
        if (!lista.contains(e.target) && e.target !== input) {
            lista.innerHTML = '';
        }
    });
});
</script>
@endpush
