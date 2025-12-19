@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="bg-white shadow rounded-lg">

        <div class="px-6 py-4 bg-blue-600 text-white font-semibold rounded-t-lg">
            📝 Registrar Resultado de Examen
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
                    <input type="hidden" name="proceso_licencia_id" id="proceso_id">

                    <div id="lista-resultados"
                         class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 hidden"></div>

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
                        <input id="tipo_licencia" class="border rounded px-3 py-2 bg-gray-100" placeholder="Licencia" disabled>
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
                    <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                        💾 Registrar Resultado
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
