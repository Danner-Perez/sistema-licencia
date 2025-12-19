@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Editar Verificación</h2>
        <a href="{{ route('verificaciones.index') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-100">⬅ Volver</a>
    </div>

    @if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('verificaciones.update', $verificacion->id_verificacion) }}" method="POST"
        class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- BUSCADOR POSTULANTE --}}
        <div class="relative">
            <label class="block text-sm font-medium mb-1">Buscar Postulante (DNI / nombres)</label>
            <input type="text" id="buscar-postulante" class="w-full border rounded-lg px-3 py-2"
                placeholder="Escriba al menos 3 caracteres"
                value="{{ $verificacion->postulante->dni }} - {{ $verificacion->postulante->nombres }} {{ $verificacion->postulante->apellidos }}"
                autocomplete="off">
            <input type="hidden" name="id_postulante" id="postulante-id" value="{{ $verificacion->id_postulante }}">
            <div id="lista-postulantes" class="absolute z-10 w-full bg-white border rounded-lg mt-1 hidden"></div>
        </div>

        {{-- PLACA --}}
        <div>
            <label class="block text-sm font-medium mb-1">Placa</label>
            <input type="text" name="placa" class="w-full border rounded-lg px-3 py-2"
                value="{{ $verificacion->placa }}" required>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('verificaciones.index') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancelar</a>
            <button class="px-5 py-2 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700">💾 Actualizar Verificación</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const input = document.getElementById('buscar-postulante');
    const selectId = document.getElementById('postulante-id');
    const lista = document.getElementById('lista-postulantes');

    input.addEventListener('input', function() {
        const query = this.value;
        if (query.length < 3) {
            lista.classList.add('hidden');
            return;
        }

        fetch("{{ route('verificaciones.buscarPostulante') }}?query=" + query)
            .then(res => res.json())
            .then(data => {
                lista.innerHTML = '';
                if (data.length === 0) {
                    lista.classList.add('hidden');
                    return;
                }

                data.forEach(postulante => {
                    const div = document.createElement('div');
                    div.classList.add('px-3', 'py-2', 'cursor-pointer', 'hover:bg-gray-100');
                    div.textContent = `${postulante.dni} - ${postulante.nombres} ${postulante.apellidos}`;
                    div.dataset.id = postulante.id_postulante;

                    div.addEventListener('click', function() {
                        input.value = this.textContent;
                        selectId.value = this.dataset.id;
                        lista.innerHTML = '';
                        lista.classList.add('hidden');
                    });

                    lista.appendChild(div);
                });
                lista.classList.remove('hidden');
            });
    });

    document.addEventListener('click', e => {
        if (!lista.contains(e.target) && e.target !== input) {
            lista.classList.add('hidden');
        }
    });
</script>
@endpush