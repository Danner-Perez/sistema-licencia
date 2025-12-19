@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="bg-white shadow rounded-lg p-6">

        <h3 class="text-lg font-semibold mb-4">✏️ Editar Examen</h3>

        <form method="POST" action="{{ route('examenes.update', $examen) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium mb-1">Resultado</label>
                <select name="resultado" class="w-full border rounded px-3 py-2" required>
                    <option value="APROBADO" @selected($examen->resultado === 'APROBADO')>
                        APROBADO
                    </option>
                    <option value="NO APROBADO" @selected($examen->resultado === 'NO APROBADO')>
                        NO APROBADO
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Observación</label>
                <textarea name="observacion"
                          class="w-full border rounded px-3 py-2"
                          rows="3">{{ $examen->observacion }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('examenes.index') }}"
                   class="px-4 py-2 border rounded hover:bg-gray-100">
                    Cancelar
                </a>

                <button class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    💾 Guardar Cambios
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
