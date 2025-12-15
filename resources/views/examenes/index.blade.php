@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold mb-4">Resultados de Examen</h2>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">DNI</th>
            <th class="p-2">Postulante</th>
            <th class="p-2">Resultado</th>
            <th class="p-2">Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($postulantes as $p)
        <tr class="border-t">
            <td class="p-2">{{ $p->dni }}</td>
            <td class="p-2">{{ $p->nombres }} {{ $p->apellidos }}</td>

            <td class="p-2">
                @if($p->resultado === 'APROBADO')
                    <span class="text-green-600 font-bold">APROBADO</span>
                @elseif($p->resultado === 'NO APROBADO')
                    <span class="text-red-600 font-bold">NO APROBADO</span>
                @else
                    <span class="text-gray-500">PENDIENTE</span>
                @endif
            </td>

            <td class="p-2">
                <form method="POST" action="{{ route('examenes.resultado') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id }}">

                    <button name="resultado" value="APROBADO"
                            class="bg-green-600 text-white px-2 py-1 rounded">
                        Aprobar
                    </button>

                    <button name="resultado" value="NO APROBADO"
                            class="bg-red-600 text-white px-2 py-1 rounded">
                        Desaprobar
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
