@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Verificación</h2>

    <form action="{{ route('verificaciones.update', $verificacion) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- BUSCADOR DINÁMICO POR DNI / NOMBRE --}}
        <label>Postulante</label>
        <input type="text" id="buscar-postulante" class="form-control mb-2"
               placeholder="Escribe DNI o nombre..."
               value="{{ $verificacion->postulante->dni }} - {{ $verificacion->postulante->nombres }} {{ $verificacion->postulante->apellidos }}">
        <input type="hidden" name="id_postulante" id="id_postulante" value="{{ $verificacion->id_postulante }}">

        <ul id="resultado-postulante" class="list-group mb-3" style="position: absolute; z-index: 1000; width: 50%; display: none;"></ul>

        <label>Placa</label>
        <input type="text" name="placa" class="form-control mb-3" value="{{ $verificacion->placa }}" required>

        <button class="btn btn-warning">Actualizar</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
const input = document.getElementById('buscar-postulante');
const selectId = document.getElementById('id_postulante');
const resultado = document.getElementById('resultado-postulante');

input.addEventListener('input', function() {
    const query = this.value;

    if(query.length < 2) { 
        resultado.style.display = 'none';
        return;
    }

    fetch("{{ route('verificaciones.buscarPostulante') }}?query=" + query)
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = '';
            if(data.length === 0){
                resultado.style.display = 'none';
                return;
            }

            data.forEach(postulante => {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'list-group-item-action');
                li.textContent = `${postulante.dni} - ${postulante.nombres} ${postulante.apellidos}`;
                li.dataset.id = postulante.id_postulante;
                li.style.cursor = 'pointer';

                li.addEventListener('click', function() {
                    input.value = this.textContent;
                    selectId.value = this.dataset.id;
                    resultado.innerHTML = '';
                    resultado.style.display = 'none';
                });

                resultado.appendChild(li);
            });
            resultado.style.display = 'block';
        });
});

// Ocultar lista al hacer clic fuera
document.addEventListener('click', function(e){
    if(!resultado.contains(e.target) && e.target !== input){
        resultado.style.display = 'none';
    }
});
</script>
@endpush
