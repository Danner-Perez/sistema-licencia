@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- CABECERA --}}
    <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
        <h2 class="text-xl font-bold text-gray-800">
            📋 Exámenes del día
        </h2>

        <div class="flex gap-2">
            <a href="{{ route('examenes.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                ➕ Registrar Examen
            </a>

            <a href="{{ route('examenes.exportarHoy') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                📥 Exportar Hoy
            </a>
        </div>
    </div>

    {{-- MENSAJES --}}
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLA --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">DNI</th>
                    <th class="px-4 py-3">Postulante</th>
                    <th class="px-4 py-3">Licencia</th>
                    <th class="px-4 py-3">Placa</th>
                    <th class="px-4 py-3">Resultado</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($postulantes as $i => $p)
                @php
                    $examen = $p->examenes->first();
                    $verificacion = $p->verificaciones->first();
                @endphp

                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $i + 1 }}</td>
                    <td class="px-4 py-2">{{ $p->dni }}</td>
                    <td class="px-4 py-2">{{ $p->nombres }} {{ $p->apellidos }}</td>

                    <td class="px-4 py-2">
                        {{ $p->procesoActivo->tipo_licencia ?? '—' }}
                    </td>

                    <td class="px-4 py-2">
                        {{ $verificacion?->placa ?? '—' }}
                    </td>

                    <td class="px-4 py-2">
                        @if ($examen?->resultado === 'APROBADO')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                APROBADO
                            </span>
                        @elseif ($examen?->resultado === 'NO APROBADO')
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                NO APROBADO
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">
                                SIN EXAMEN
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-2">
                        {{ $examen?->fecha?->format('d/m/Y') ?? '—' }}
                    </td>

                    <td class="px-4 py-2 text-center">
                        @if($examen && in_array($examen->resultado, ['APROBADO','NO APROBADO']))
                            <a href="{{ route('examenes.edit', $examen->id_examen) }}"
                               class="text-yellow-600 hover:text-yellow-800">
                                ✏️ Editar
                            </a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500">
                        No hay exámenes registrados
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
