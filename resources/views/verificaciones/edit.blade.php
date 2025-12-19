@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- CABECERA --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Registrar Verificación</h2>
        <a href="{{ route('verificaciones.index') }}"
           class="px-4 py-2 rounded-lg border hover:bg-gray-100">
            ⬅ Volver
        </a>
    </div>

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

    <form action="{{ route('verificaciones.store') }}" method="POST"
          class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf

        {{-- BUSCADOR POSTULANTE --}}
        <div class="relative">
            <label class="block text-sm font-medium mb-1">
                Buscar Postulante (DNI / nombres)
            </label>

            <input type="text"
                   id="buscar-postulante"
                   class="w-full border rounded-lg px-3 py-2"
                   placeholder="Escriba al menos 3 caracteres"
                   autocomplete="off">

            <input type="hidden" name="id_postulante" id="postulante-id">

            <div id="lista-postulantes"
                 class="absolute z-10 w-full bg-white border rounded-lg mt-1 hidden">
            </div>

            <p id="error-busqueda" class="text-sm text-red-600 mt-1"></p>
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
