@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- TÍTULO --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">
            📋 Control de Asistencia (Hoy)
        </h2>
        <span class="text-sm text-gray-500 hidden md:block">
            {{ now()->format('d/m/Y') }}
        </span>
    </div>

    {{-- BUSCADOR --}}
    <div class="bg-white p-4 rounded-xl shadow mb-6">
        <form method="GET" action="{{ route('asistencias.index') }}"
              class="flex flex-col md:flex-row gap-3">

            <input
                type="text"
                name="dni"
                placeholder="🔍 Buscar por DNI"
                value="{{ request('dni') }}"
                class="w-full md:w-72 border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300"
            >

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                Buscar
            </button>
        </form>
    </div>

    {{-- RESUMEN --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-green-100 rounded-xl p-4 text-center">
            <p id="counterAsistidos" class="text-2xl font-bold text-green-700">
                {{ $asistidos ?? 0 }}
            </p>
            <p class="text-sm text-green-700">Asistieron</p>
        </div>

        <div class="bg-yellow-100 rounded-xl p-4 text-center">
            <p id="counterPendientes" class="text-2xl font-bold text-yellow-700">
                {{ $pendientes ?? count($postulantes) }}
            </p>
            <p class="text-sm text-yellow-700">Pendientes</p>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    {{-- OCULTO EN CELULAR --}}
                    <th class="p-3 text-left hidden md:table-cell">DNI</th>

                    <th class="p-3 text-left">Postulante</th>
                    <th class="p-3 text-left">Licencia</th>

                    {{-- OCULTO EN CELULAR --}}
                    <th class="p-3 text-center hidden md:table-cell">Hora llegada</th>

                    <th class="p-3 text-center">Acción</th>
                </tr>
            </thead>

            <tbody>
            @forelse($postulantes as $p)
                @php
                    $asistenciaHoy = $p->asistencias->first();
                @endphp

                <tr id="fila-{{ $p->id_postulante }}"
                    class="border-t hover:bg-gray-50 transition">

                    {{-- DNI (DESKTOP) --}}
                    <td class="p-3 font-mono hidden md:table-cell">
                        {{ $p->dni }}
                    </td>

                    {{-- POSTULANTE --}}
                    <td class="p-3">
                        <p class="font-medium text-gray-800">
                            {{ $p->nombres }} {{ $p->apellidos }}
                        </p>

                        {{-- INFO EXTRA SOLO EN CELULAR --}}
                        @if($asistenciaHoy)
                            <p class="text-xs text-green-600 md:hidden">
                                ⏱ {{ $asistenciaHoy->hora_llegada->format('H:i') }}
                            </p>
                        @endif
                    </td>

                    {{-- LICENCIA --}}
                    <td class="p-3">
                        @if($p->procesoActivo)
                            <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-700 font-semibold">
                                {{ $p->procesoActivo->tipo_licencia }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">N/D</span>
                        @endif
                    </td>

                    {{-- HORA LLEGADA (DESKTOP) --}}
                    <td class="p-3 text-center hidden md:table-cell">
                        @if($asistenciaHoy)
                            <span class="text-green-700 font-semibold">
                                {{ $asistenciaHoy->hora_llegada->format('H:i:s') }}
                            </span>
                        @else
                            <span class="text-gray-400">--:--</span>
                        @endif
                    </td>

                    {{-- ACCIÓN --}}
                    <td class="p-3 text-center">
                        @if($asistenciaHoy)
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                ✅ ASISTIÓ
                            </span>
                        @else
                            <button
                                class="marcar-btn bg-blue-600 hover:bg-blue-700 text-white
                                       px-4 py-1.5 rounded-full transition"
                                data-id="{{ $p->id_postulante }}">
                                Marcar
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">
                        No hay postulantes registrados hoy
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- TOAST --}}
<div id="toast"
     class="fixed bottom-6 right-6 hidden px-5 py-3 rounded-lg shadow-lg
            text-white text-sm transition-all duration-300">
</div>

{{-- MODAL CONFIRMAR --}}
<div id="modalConfirm"
     class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6">
        <h3 class="text-lg font-semibold mb-3">Confirmar asistencia</h3>
        <p class="text-sm text-gray-600 mb-6">
            ¿Deseas registrar la asistencia del postulante?
        </p>

        <div class="flex justify-end gap-3">
            <button id="cancelConfirm"
                    class="px-4 py-2 rounded-lg border text-gray-600">
                Cancelar
            </button>
            <button id="acceptConfirm"
                    class="px-5 py-2 rounded-lg bg-blue-600 text-white">
                Sí, registrar
            </button>
        </div>
    </div>
</div>




<script>
document.addEventListener('DOMContentLoaded', () => {

    let postulantePendiente = null;

    const counterAsistidos = document.getElementById('counterAsistidos');
    const counterPendientes = document.getElementById('counterPendientes');

    // Click en botón Marcar
    document.addEventListener('click', e => {
        if (!e.target.classList.contains('marcar-btn')) return;

        postulantePendiente = e.target;
        const modal = document.getElementById('modalConfirm');
        modal.classList.remove('hidden');
        modal.children[0].classList.add('scale-100'); // animación modal
    });

    // Cancelar
    document.getElementById('cancelConfirm').addEventListener('click', () => {
        postulantePendiente = null;
        const modal = document.getElementById('modalConfirm');
        modal.children[0].classList.remove('scale-100');
        setTimeout(() => modal.classList.add('hidden'), 200);
    });

    // Confirmar
    document.getElementById('acceptConfirm').addEventListener('click', () => {
        if (!postulantePendiente) return;

        const btn = postulantePendiente;
        const postulanteId = btn.dataset.id;

        const modal = document.getElementById('modalConfirm');
        modal.children[0].classList.remove('scale-100');
        setTimeout(() => modal.classList.add('hidden'), 200);

        btn.disabled = true;
        btn.textContent = '⏳ Marcando...';
        btn.classList.add('opacity-60');

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
                fila.querySelector('td:nth-child(4)').innerText =
                    new Date().toLocaleTimeString('es-PE');

                fila.querySelector('td:nth-child(5)').innerHTML = `
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-semibold">
                        ✅ ASISTIÓ
                    </span>`;
                fila.classList.add('bg-green-50');

                // Actualizar contadores
                counterAsistidos.textContent = parseInt(counterAsistidos.textContent) + 1;
                counterPendientes.textContent = parseInt(counterPendientes.textContent) - 1;

                showToast(data.message, 'success');
            } else {
                resetBtn(btn);
                showToast(data.message, 'error');
            }
        })
        .catch(() => {
            resetBtn(btn);
            showToast('Error de conexión', 'error');
        });

        postulantePendiente = null;
    });

    function resetBtn(btn) {
        btn.disabled = false;
        btn.textContent = 'Marcar';
        btn.classList.remove('opacity-60');
    }

    function showToast(message, type) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `fixed bottom-6 right-6 px-5 py-3 rounded-lg shadow-lg text-white text-sm
            transition-all duration-300
            ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }
});
</script>
@endsection
