@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold mb-4">Exportar Resultados</h2>

<form method="GET" action="{{ route('examenes.exportar') }}"
      class="bg-white p-6 shadow rounded">

    <label>Fecha</label>
    <input type="date" name="fecha" class="border p-2 w-full mb-4">

    <label>Resultado</label>
    <select name="resultado" class="border p-2 w-full mb-4">
        <option value="">Todos</option>
        <option value="APROBADO">Aprobados</option>
        <option value="NO APROBADO">No Aprobados</option>
    </select>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Exportar a Excel
    </button>
</form>
@endsection
