@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="bg-white shadow rounded-lg">

        {{-- CABECERA --}}
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                ✏️ Editar Postulante
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

            <form method="POST" action="{{ route('postulantes.update', $postulante) }}">
                @csrf
                @method('PUT')

                {{-- DNI --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">DNI</label>
                    <input type="text"
                           value="{{ $postulante->dni }}"
                           class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                           disabled>
                </div>

                {{-- NOMBRES --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nombres</label>
                    <input type="text"
                           name="nombres"
                           value="{{ old('nombres', $postulante->nombres) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                           required>
                </div>

                {{-- APELLIDOS --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Apellidos</label>
                    <input type="text"
                           name="apellidos"
                           value="{{ old('apellidos', $postulante->apellidos) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                           required>
                </div>

                {{-- TIPO LICENCIA --}}
                @if($postulante->procesoActivo)
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Tipo de Licencia</label>
                        <select name="tipo_licencia"
                                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                required>
                            @foreach(['A1','A2A','A2B','A3'] as $tipo)
                                <option value="{{ $tipo }}"
                                    @selected($postulante->procesoActivo->tipo_licencia === $tipo)>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- FECHA PSICOSOMÁTICO --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Fecha Psicosomático
                    </label>
                    <input type="date"
                           name="fecha_psicosomatico"
                           value="{{ old('fecha_psicosomatico', $postulante->fecha_psicosomatico?->format('Y-m-d')) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                           required>
                </div>

                {{-- VALIDACIÓN RENIEC --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">
                        Validación RENIEC
                    </label>
                    <select name="validado_reniec"
                            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="0" @selected(!$postulante->validado_reniec)>
                            ❌ No validado
                        </option>
                        <option value="1" @selected($postulante->validado_reniec)>
                            ✅ Validado
                        </option>
                    </select>
                </div>

                {{-- BOTONES --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('postulantes.index') }}"
                       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                        Volver
                    </a>

                    <button class="px-5 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                        💾 Guardar Cambios
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
