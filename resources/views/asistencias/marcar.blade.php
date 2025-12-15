@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold mb-4">Asistencia de Postulantes</h2>

<form method="POST" action="{{ route('asistencias.store') }}"
      class="bg-white p-6 shadow rounded mb-6">
    @csrf

    <label class="block mb-2 font-semibold">DNI del Postulante</label>
    <input type="text" name="dni"
           class="border p-2 w-full mb-4"
           placeholder="Ingrese DNI">

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Marcar Asistencia
    </button>
</form>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">DNI</th>
            <th class="p-2">Nombre</th>
            <th class="p-2">Hora Llegada</th>
            <th class="p-2">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asistencias as $a)
        <tr class="border-t">
            <td class="p-2">{{ $a->postulante->dni }}</td>
            <td class="p-2">
                {{ $a->postulante->nombres }} {{ $a->postulante->apellidos }}
            </td>
            <td class="p-2">{{ $a->hora_llegada }}</td>
            <td class="p-2 text-green-600 font-bold">ASISTIÓ</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
