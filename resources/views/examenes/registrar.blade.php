@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- TOASTS DE MENSAJE --}}
    @if(session('success'))
        <div x-data="{ show: true }"
            x-show="show"
            x-transition.opacity
            x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }"
            x-show="show"
            x-transition.opacity
            x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-4 right-4 z-50 bg-red-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('error') }}
        </div>
    @endif


    <div class="bg-white shadow rounded-lg">

        <div class="px-6 py-4 bg-blue-600 text-white font-semibold rounded-t-lg flex justify-between items-center">
            <span>📝 Registrar Resultado de Examen</span>
            <a href="{{ route('examenes.index') }}"
               class="px-4 py-2 rounded-lg border hover:bg-blue-700 hover:text-white transition">
                ⬅ Volver
            </a>
        </div>

        <div class="p-6">

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

            <form method="POST" action="{{ route('examenes.store') }}">
                @csrf

                {{-- BUSCAR DNI --}}
                <div class="mb-6 relative">
                    <label class="block font-medium mb-1">Buscar Postulante (DNI)</label>
                    <input type="text" id="buscar-dni"
                           class="w-full border rounded-lg px-4 py-2"
                           placeholder="Ingrese DNI"
                           autocomplete="off">

                    <input type="hidden" name="id_postulante" id="postulante_id">

                    <div id="lista-resultados"
                         class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 hidden max-h-60 overflow-y-auto"></div>

                    <p id="error" class="text-sm text-red-600 mt-1"></p>
                </div>

                {{-- DATOS POSTULANTE --}}
                <div class="border rounded-lg mb-6">
                    <div class="px-4 py-2 bg-gray-100 font-semibold">
                        👤 Datos del Postulante
                    </div>

                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input id="dni" class="border rounded px-3 py-2 bg-gray-100" placeholder="DNI" disabled>
                        <input id="nombre" class="border rounded px-3 py-2 bg-gray-100" placeholder="Nombres" disabled>
                        <input id="apellidos" class="border rounded px-3 py-2 bg-gray-100" placeholder="Apellidos" disabled>
                        <input id="placa" class="border rounded px-3 py-2 bg-gray-100" placeholder="Placa" disabled>
                    </div>
                </div>

                {{-- RESULTADO --}}
                <div class="border rounded-lg mb-6">
                    <div class="px-4 py-2 bg-gray-100 font-semibold">
                        📊 Resultado del Examen
                    </div>

                    <div class="p-4 space-y-4">
                        <select name="resultado" class="w-full border rounded px-3 py-2" required>
                            <option value="">Seleccione</option>
                            <option value="APROBADO">✅ APROBADO</option>
                            <option value="NO APROBADO">❌ NO APROBADO</option>
                        </select>

                        <textarea name="observacion"
                                  class="w-full border rounded px-3 py-2"
                                  rows="2"
                                  placeholder="Observación (opcional)"></textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        💾 Registrar Resultado
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const input = document.getElementById('buscar-dni');
const lista = document.getElementById('lista-resultados');
const postulanteId = document.getElementById('postulante_id');
const dni = document.getElementById('dni');
const nombre = document.getElementById('nombre');
const apellidos = document.getElementById('apellidos');
const placa = document.getElementById('placa');

input.addEventListener('input', function() {
    const query = this.value.trim();

    if(query.length < 3){
        lista.classList.add('hidden');
        dni.value = nombre.value = apellidos.value = placa.value = '';
        postulanteId.value = '';
        return;
    }

    fetch("{{ route('examenes.buscar') }}?query=" + query)
        .then(res => res.json())
        .then(data => {
            lista.innerHTML = '';
            if(data.length === 0){
                lista.classList.add('hidden');
                dni.value = nombre.value = apellidos.value = placa.value = '';
                postulanteId.value = '';
                return;
            }

            data.forEach(p => {
                const div = document.createElement('div');
                div.classList.add('px-3','py-2','cursor-pointer','hover:bg-gray-100');
                div.textContent = `${p.dni} - ${p.nombres} ${p.apellidos}`;
                div.dataset.id = p.id_postulante;
                div.dataset.dni = p.dni;
                div.dataset.nombre = p.nombres;
                div.dataset.apellidos = p.apellidos;
                div.dataset.placa = p.placa;

                div.addEventListener('click', function() {
                    input.value = this.dataset.dni;
                    postulanteId.value = this.dataset.id;
                    dni.value = this.dataset.dni;
                    nombre.value = this.dataset.nombre;
                    apellidos.value = this.dataset.apellidos;
                    placa.value = this.dataset.placa || '';
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
