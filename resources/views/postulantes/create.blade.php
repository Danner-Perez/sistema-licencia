@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="bg-white shadow rounded-lg">

        {{-- CABECERA --}}
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                ➕ Registro de Postulante
            </h2>
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

            <form method="POST" action="{{ route('postulantes.store') }}">
                @csrf

                {{-- DNI --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">DNI</label>
                    <input type="text"
                           name="dni"
                           maxlength="8"
                           value="{{ old('dni') }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                           required>
                </div>

                {{-- NOMBRES --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nombres</label>
                    <input type="text"
                           name="nombres"
                           value="{{ old('nombres') }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                           required>
                </div>

                {{-- APELLIDOS --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Apellidos</label>
                    <input type="text"
                           name="apellidos"
                           value="{{ old('apellidos') }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                           required>
                </div>

                <hr class="my-6">

                {{-- TIPO LICENCIA --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tipo de Licencia</label>
                    <select name="tipo_licencia"
                            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                            required>
                        <option value="">-- Seleccione --</option>
                        @foreach(['A1','A2A','A2B','A3'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo_licencia')==$tipo)>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- FECHA PSICOSOMÁTICO --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">
                        Fecha Psicosomático
                    </label>
                    <input type="date"
                           name="fecha_psicosomatico"
                           value="{{ old('fecha_psicosomatico') }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                           required>
                    <p class="text-xs text-gray-500 mt-1">
                        Vigencia: 6 meses
                    </p>
                </div>

                {{-- BOTONES --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('postulantes.index') }}"
                       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                        Cancelar
                    </a>

                    <button class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                        💾 Guardar Postulante
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
