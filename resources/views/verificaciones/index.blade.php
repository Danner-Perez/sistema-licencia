@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- CABECERA --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">
            📋 Postulantes Verificados
        </h2>

        <a href="{{ route('verificaciones.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            ➕ Nueva Verificación
        </a>
    </div>

    {{-- TABLA --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">DNI</th>
                    <th class="px-4 py-3">Postulante</th>
                    <th class="px-4 py-3">Placa</th>
                    <th class="px-4 py-3">Fecha y Hora</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($verificaciones as $i => $v)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $i + 1 }}</td>

                    <td class="px-4 py-2">
                        {{ $v->postulante->dni ?? '—' }}
                    </td>

                    <td class="px-4 py-2">
                        {{ $v->postulante->nombres ?? '' }}
                        {{ $v->postulante->apellidos ?? '' }}
                    </td>

                    <td class="px-4 py-2 font-semibold">
                        {{ $v->placa }}
                    </td>

                    <td class="px-4 py-2">
                        {{ $v->fecha?->format('d/m/Y H:i') ?? '—' }}
                    </td>

                    <td class="px-4 py-2 text-center space-x-2">
                        <a href="{{ route('verificaciones.edit', $v->id_verificacion) }}"
                           class="text-yellow-600 hover:text-yellow-800">
                            ✏️
                        </a>

                        <form action="{{ route('verificaciones.destroy', $v->id_verificacion) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('¿Eliminar esta verificación?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        No hay postulantes verificados
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
