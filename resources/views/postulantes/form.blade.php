@props([
    'postulante' => null,
    'readonlyDni' => false
])

{{-- DNI --}}
<div>
    <label class="block text-sm font-medium mb-1">DNI</label>
    <input
        type="text"
        name="dni"
        maxlength="8"
        value="{{ old('dni', $postulante->dni ?? '') }}"
        @if($readonlyDni) disabled @endif
        class="w-full border rounded-lg px-3 py-2 {{ $readonlyDni ? 'bg-gray-100' : '' }}"
        required
    >
</div>

{{-- NOMBRES --}}
<div>
    <label class="block text-sm font-medium mb-1">Nombres</label>
    <input
        type="text"
        name="nombres"
        value="{{ old('nombres', $postulante->nombres ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
        required
    >
</div>

{{-- APELLIDOS --}}
<div>
    <label class="block text-sm font-medium mb-1">Apellidos</label>
    <input
        type="text"
        name="apellidos"
        value="{{ old('apellidos', $postulante->apellidos ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
        required
    >
</div>

{{-- TIPO LICENCIA --}}
<div>
    <label class="block text-sm font-medium mb-1">Tipo de Licencia</label>
    <select
        name="tipo_licencia"
        class="w-full border rounded-lg px-3 py-2"
        required
    >
        <option value="">-- Seleccione --</option>

        @php
            $tipos = ['A-I','A-IIa','A-IIb','A-IIIa','A-IIIb','A-IIIc'];
            $actual = old(
                'tipo_licencia',
                $postulante?->procesoActivo?->tipo_licencia
            );
        @endphp

        @foreach($tipos as $tipo)
            <option value="{{ $tipo }}" @selected($actual === $tipo)>
                {{ $tipo }}
            </option>
        @endforeach
    </select>
</div>

{{-- FECHA PSICOSOMÁTICO --}}
<div>
    <label class="block text-sm font-medium mb-1">Fecha Psicosomático</label>
    <input
        type="date"
        name="fecha_psicosomatico"
        value="{{ old(
            'fecha_psicosomatico',
            $postulante?->fecha_psicosomatico?->format('Y-m-d')
        ) }}"
        class="w-full border rounded-lg px-3 py-2"
        required
    >
    <p class="text-xs text-gray-500 mt-1">
        Vigencia: 6 meses
    </p>
</div>
