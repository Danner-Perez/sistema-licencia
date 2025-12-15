@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold mb-4">Verificación de Vehículo</h2>

<form method="POST" action="{{ route('verificaciones.store') }}"
      class="bg-white p-6 shadow rounded mb-6">
    @csrf

    <label class="block mb-2">DNI del Postulante</label>
    <input type="text" name="dni"
           class="border p-2 w-full mb-3"
           placeholder="Ingrese DNI">

    <label class="block mb-2">Placa del Vehículo</label>
    <input type="text" name="placa"
           class="border p-2 w-full mb-4"
           placeholder="Ejemplo: ABC-123">

    <button class="bg-green-600 text-white px-4 py-2 rounded">
        Registrar Verificación
    </button>
</form>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">DNI</th>
            <th class="p-2">Postulante</th>
            <th class="p-2">Placa</th>
            <th class="p-2">Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($verificaciones as $v)
        <tr class="border-t">
            <td class="p-2">{{ $v->postulante->dni }}</td>
            <td class="p-2">
                {{ $v->postulante->nombres }} {{ $v->postulante->apellidos }}
            </td>
            <td class="p-2 font-semibold">{{ $v->placa }}</td>
            <td class="p-2">{{ $v->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
