@extends('layouts.app')

@section('content')
{{-- TOAST ERROR --}}
@if ($errors->any())
<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 4000)"
    x-show="show"
    x-transition
    class="fixed top-5 right-5 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50"
>
    ❌ {{ $errors->first() }}
</div>
@endif
@if ($errors->any())
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('openCreateOnError', true)
    })
</script>
@endif

{{-- TOAST SUCCESS --}}
@if(session('success'))
<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition
    class="fixed top-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    ✅ {{ session('success') }}
</div>
@endif

<div
    x-data="{
        openCreate: false,
        openEdit: false,
        postulante: {}
    }"
>

    {{-- TÍTULO --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">
            👥 Gestión de Postulantes
        </h1>

        <button
            @click="openCreate = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            ➕ Nuevo Postulante
        </button>
    </div>

    {{-- FILTROS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <input type="text" id="buscar"
               placeholder="🔍 Buscar por DNI o nombre"
               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200">

        <form method="GET" class="flex gap-2">
            <input type="date" name="fecha"
                   value="{{ request('fecha', now()->toDateString()) }}"
                   class="rounded-lg border-gray-300 focus:ring focus:ring-blue-200">
            <button
                class="bg-gray-800 hover:bg-gray-900 text-white px-4 rounded-lg">
                Buscar
            </button>
        </form>
    </div>

    {{-- TABLA --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-900 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">DNI</th>
                    <th class="px-4 py-3 text-left">Postulante</th>
                    <th class="px-4 py-3 text-left">Licencia</th>
                    <th class="px-4 py-3 text-left">Psicosomático</th>
                    <th class="px-4 py-3 text-left">Vence</th>
                    <th class="px-4 py-3 text-center">Días</th>
                    <th class="px-4 py-3 text-center">Estado</th>
                    <th class="px-4 py-3 text-center">Acción</th>
                </tr>
            </thead>

            <tbody id="tabla" class="divide-y">
            @forelse ($postulantes as $p)
                @php
                    $proceso = $p->procesoActivo;
                    $vence = $p->fechaVencimientoPsicosomatico();
                    $dias = $p->diasRestantesPsicosomatico();
                    $estado = $dias === null ? 'SIN REGISTRO' :
                              ($dias < 0 ? 'VENCIDO' : ($dias <= 7 ? 'POR VENCER' : 'VIGENTE'));
                @endphp

                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $p->dni }}</td>

                    <td class="px-4 py-3">
                        <div class="font-medium">
                            {{ $p->nombres }} {{ $p->apellidos }}
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        {{ $proceso->tipo_licencia ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $p->fecha_psicosomatico?->format('d/m/Y') ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $vence?->format('d/m/Y') ?? '—' }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        @if($dias !== null)
                            <span class="font-bold {{ $dias <= 7 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $dias }}
                            </span>
                        @else
                            —
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        @switch($estado)
                            @case('VIGENTE')
                                <span class="px-2 py-1 text-xs rounded bg-green-600 text-white">
                                    Vigente
                                </span>
                                @break
                            @case('POR VENCER')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-400 text-gray-900">
                                    Por vencer
                                </span>
                                @break
                            @case('VENCIDO')
                                <span class="px-2 py-1 text-xs rounded bg-red-600 text-white">
                                    Vencido
                                </span>
                                @break
                            @default
                                <span class="px-2 py-1 text-xs rounded bg-gray-400 text-white">
                                    Sin registro
                                </span>
                        @endswitch
                    </td>

                    <td class="px-4 py-3 text-center">
                        <button
                            @click="
                                postulante = {
                                    id: {{ $p->id_postulante }},
                                    dni: '{{ $p->dni }}',
                                    nombres: '{{ $p->nombres }}',
                                    apellidos: '{{ $p->apellidos }}',
                                    fecha_psicosomatico: '{{ optional($p->fecha_psicosomatico)->format('Y-m-d') }}',
                                    tipo_licencia: '{{ $p->procesoActivo->tipo_licencia ?? '' }}'
                                };
                                openEdit = true;
                            "

                            class="text-blue-600 hover:text-blue-800 font-medium"
                        >
                            ✏️ Editar
                        </button>


                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500">
                        No hay postulantes registrados
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODALES --}}
    @include('postulantes.modal-create')
    @include('postulantes.modal-edit')

</div>

@endsection

@push('scripts')
<script>
document.getElementById('buscar').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('#tabla tr').forEach(row => {
        row.classList.toggle(
            'hidden',
            !row.innerText.toLowerCase().includes(value)
        );
    });
});
</script>
@endpush
