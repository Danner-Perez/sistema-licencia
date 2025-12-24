@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- Mensajes flotantes --}}
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-4 right-4 z-50">
        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Cabecera --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">📋 Postulantes Verificados</h2>

        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
            <form method="GET" action="{{ route('verificaciones.index') }}" class="flex flex-col md:flex-row gap-2 w-full md:w-auto">

            <input
                type="date"
                name="fecha"
                value="{{ request('fecha', now()->toDateString()) }}"
                class="px-3 py-2 border rounded focus:ring focus:ring-blue-300"
            >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar DNI o nombre"
                class="px-4 py-2 border rounded w-full md:w-64 focus:ring focus:ring-blue-300"
            >

            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filtrar
            </button>

        </form>

            <a href="{{ route('verificaciones.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 whitespace-nowrap">➕ Nueva Verificación</a>
        </div>
    </div>

    {{-- Tabla responsive --}}
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-3 text-center">#</th>
                    <th class="px-4 py-3">DNI</th>
                    <th class="px-4 py-3">Postulante</th>
                    <th class="px-4 py-3">Placa</th>
                    <th class="px-4 py-3">Fecha y Hora</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($verificaciones as $i => $v)
                    <x-verificacion-row :verificacion="$v" :index="$i" />
                @empty
                    <tr>
                       <td colspan="6" class="text-center py-6 text-gray-500">
                            No hay verificaciones registradas para la fecha seleccionada
                        </td>

                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4 flex justify-end">
        {{ $verificaciones->links() }}
    </div>
</div>
@endsection
