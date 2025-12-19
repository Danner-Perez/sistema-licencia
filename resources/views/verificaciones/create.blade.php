@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- CABECERA --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Registrar Verificación</h2>
        <a href="{{ route('verificaciones.index') }}"
           class="px-4 py-2 rounded-lg border hover:bg-gray-100">
            ⬅ Volver
        </a>
    </div>

    {{-- TOASTS DE MENSAJE --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-4 right-4 z-50 bg-red-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORMULARIO --}}
    <form action="{{ route('verificaciones.store') }}" method="POST"
          class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf

        {{-- BUSCADOR POSTULANTE --}}
        <div class="relative">
            <label class="block text-sm font-medium mb-1">Buscar Postulante (DNI / nombres)</label>
            <input type="text"
                   id="buscar-postulante"
                   class="w-full border rounded-lg px-3 py-2"
                   placeholder="Escriba al menos 3 caracteres"
                   autocomplete="off">

            <input type="hidden" name="id_postulante" id="postulante-id">

            <div id="lista-postulantes"
                 class="absolute z-10 w-full bg-white border rounded-lg mt-1 hidden max-h-60 overflow-y-auto shadow">
            </div>
        </div>

        {{-- NOMBRE Y APELLIDOS AUTORELLENADOS --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nombres</label>
                <input type="text" id="nombres" class="w-full border rounded-lg px-3 py-2 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Apellidos</label>
                <input type="text" id="apellidos" class="w-full border rounded-lg px-3 py-2 bg-gray-100" readonly>
            </div>
        </div>

        {{-- PLACA --}}
        <div>
            <label class="block text-sm font-medium mb-1">Placa</label>
            <input type="text"
                   name="placa"
                   class="w-full border rounded-lg px-3 py-2"
                   required>
        </div>

        {{-- BOTONES --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('verificaciones.index') }}"
               class="px-4 py-2 rounded-lg border hover:bg-gray-100">
                Cancelar
            </a>
            <button class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                💾 Guardar Verificación
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const input = document.getElementById('buscar-postulante');
const selectId = document.getElementById('postulante-id');
const lista = document.getElementById('lista-postulantes');
const nombres = document.getElementById('nombres');
const apellidos = document.getElementById('apellidos');

input.addEventListener('input', function() {
    const query = this.value.trim();

    if(query.length < 3){ 
        lista.classList.add('hidden');
        nombres.value = '';
        apellidos.value = '';
        selectId.value = '';
        return;
    }

    fetch("{{ route('verificaciones.buscarPostulante') }}?query=" + query)
        .then(response => response.json())
        .then(data => {
            lista.innerHTML = '';
            if(data.length === 0){
                lista.classList.add('hidden');
                nombres.value = '';
                apellidos.value = '';
                selectId.value = '';
                return;
            }

            data.forEach(postulante => {
                const div = document.createElement('div');
                div.classList.add('px-3','py-2','cursor-pointer','hover:bg-gray-100');
                div.textContent = `${postulante.dni} - ${postulante.nombres} ${postulante.apellidos}`;
                div.dataset.id = postulante.id_postulante;
                div.dataset.nombres = postulante.nombres;
                div.dataset.apellidos = postulante.apellidos;

                div.addEventListener('click', function() {
                    input.value = postulante.dni;
                    selectId.value = this.dataset.id;
                    nombres.value = this.dataset.nombres;
                    apellidos.value = this.dataset.apellidos;
                    lista.innerHTML = '';
                    lista.classList.add('hidden');
                });

                lista.appendChild(div);
            });
            lista.classList.remove('hidden');
        });
});

// Ocultar lista al hacer clic fuera
document.addEventListener('click', function(e){
    if(!lista.contains(e.target) && e.target !== input){
        lista.classList.add('hidden');
    }
});
</script>
@endpush
