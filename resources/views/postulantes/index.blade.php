@extends('layouts.app')

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Listado de Postulantes</h2>
    <a href="{{ route('postulantes.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded">
       + Nuevo
    </a>
</div>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">DNI</th>
            <th class="p-2">Nombres</th>
            <th class="p-2">Licencia</th>
            <th class="p-2">RENIEC</th>
        </tr>
    </thead>
    <tbody>
        @foreach($postulantes as $p)
        <tr class="border-t">
            <td class="p-2">{{ $p->dni }}</td>
            <td class="p-2">{{ $p->nombres }} {{ $p->apellidos }}</td>
            <td class="p-2">{{ $p->tipo_licencia }}</td>
            <td class="p-2">
                @if($p->validado_reniec)
                    <span class="text-green-600">✔</span>
                @else
                    <span class="text-red-600">✘</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
