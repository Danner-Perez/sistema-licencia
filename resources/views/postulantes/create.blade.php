@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold mb-4">Registro de Postulante</h2>

<form method="POST" action="{{ route('postulantes.store') }}" class="bg-white p-6 shadow rounded">
    @csrf

    <label>DNI</label>
    <input type="text" name="dni" class="border p-2 w-full mb-3">

    <label>Nombres</label>
    <input type="text" name="nombres" class="border p-2 w-full mb-3">

    <label>Apellidos</label>
    <input type="text" name="apellidos" class="border p-2 w-full mb-3">

    <label>Tipo Licencia</label>
    <select name="tipo_licencia" class="border p-2 w-full mb-4">
        <option>A1</option>
        <option>A2A</option>
        <option>A2B</option>
        <option>A3</option>
    </select>

    <button class="bg-green-600 text-white px-4 py-2 rounded">
        Guardar
    </button>
</form>
@endsection
