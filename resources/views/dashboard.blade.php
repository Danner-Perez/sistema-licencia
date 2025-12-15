@extends('layouts.app')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="bg-white p-4 shadow rounded">
        <h3 class="text-gray-500">Postulantes Hoy</h3>
        <p class="text-3xl font-bold">{{ $totalHoy ?? 0 }}</p>
    </div>

    <div class="bg-green-100 p-4 shadow rounded">
        <h3 class="text-gray-500">Aprobados</h3>
        <p class="text-3xl font-bold text-green-700">{{ $aprobados ?? 0 }}</p>
    </div>

    <div class="bg-red-100 p-4 shadow rounded">
        <h3 class="text-gray-500">Desaprobados</h3>
        <p class="text-3xl font-bold text-red-700">{{ $desaprobados ?? 0 }}</p>
    </div>
</div>
@endsection
