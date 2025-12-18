@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- TÍTULO --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            📋 Control de Asistencia
        </h2>
        <span class="text-sm text-gray-500">
            {{ now()->format('d/m/Y') }}
        </span>
    </div>

    {{-- BUSCADOR --}}
    <div class="bg-white p-4 rounded-xl shadow mb-6">
        <form method="GET" action="{{ route('asistencias.index') }}" class="flex gap-3">
            <input type="text"
                   name="dni"
                   placeholder="🔍 Buscar por DNI"
                   value="{{ request('dni') }}"
                   class="w-72 border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                Buscar
            </button>
        </form>
    </div>

    {{-- TABLA --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 text-gray-600 text-sm">
                <tr>
                    <th class="p-3 text-left">DNI</th>
                    <th class="p-3 text-left">Postulante</th>
                    <th class="p-3 text-left">Licencia</th>
                    <th class="p-3 text-center">Estado</th>
                </tr>
            </thead>

            <tbody>
                @forelse($postulantes as $p)
                <tr id="fila-{{ $p->id_postulante }}" class="border-t hover:bg-gray-50 transition">
                    <td class="p-3 font-mono text-gray-700">{{ $p->dni }}</td>

                    <td class="p-3">
                        <div class="font-semibold text-gray-800">
                            {{ $p->nombres }} {{ $p->apellidos }}
                        </div>
                    </td>

                    <td class="p-3 text-sm text-gray-600">
                        {{ optional($p->procesoActivo)->tipo_licencia ?? 'N/D' }}
                    </td>

                    <td class="p-3 text-center">
                        @if(in_array($p->id_postulante, $asistenciasHoy))
                            <span class="inline-flex items-center gap-1 px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-semibold">
                                ✅ ASISTIÓ
                            </span>
                        @else
                            <button
                                class="marcar-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-full text-sm transition active:scale-95"
                                data-id="{{ $p->id_postulante }}">
                                Marcar
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-6 text-gray-500">
                        No hay postulantes registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- TOAST --}}
<div id="toast"
     class="fixed bottom-6 right-6 hidden px-5 py-3 rounded-lg shadow-lg text-white text-sm transition">
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.marcar-btn').forEach(btn => {
        btn.addEventListener('click', () => {

            const postulanteId = btn.dataset.id;
            btn.disabled = true;
            btn.innerText = 'Marcando...';

            fetch("{{ route('asistencias.marcar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ postulante_id: postulanteId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const fila = document.getElementById('fila-' + postulanteId);
                    fila.querySelector('td:last-child').innerHTML = `
                        <span class="inline-flex items-center gap-1 px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-semibold">
                            ✅ ASISTIÓ
                        </span>`;
                    showToast(data.message, 'success');
                } else {
                    btn.disabled = false;
                    btn.innerText = 'Marcar';
                    showToast(data.message, 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerText = 'Marcar';
                showToast('Error de conexión', 'error');
            });
        });
    });

    function showToast(message, type) {
        const toast = document.getElementById('toast');
        toast.innerText = message;

        toast.className = `fixed bottom-6 right-6 px-5 py-3 rounded-lg shadow-lg text-white text-sm
            ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;

        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }
});
</script>
@endsection
