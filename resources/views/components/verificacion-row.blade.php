@props(['verificacion', 'index'])

<tr x-data="{ openDelete: false }" class="hover:bg-gray-50 cursor-pointer">
    <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
    <td class="px-4 py-2">{{ optional($verificacion->postulante)->dni ?? '—' }}</td>
    <td class="px-4 py-2">{{ optional($verificacion->postulante)->nombres ?? '' }} {{ optional($verificacion->postulante)->apellidos ?? '' }}</td>
    <td class="px-4 py-2 font-semibold">{{ $verificacion->placa }}</td>
    <td class="px-4 py-2">
        {{ $verificacion->fecha?->format('d/m/Y H:i') ?? '—' }}
        <span class="text-gray-400 text-xs">({{ $verificacion->fecha?->diffForHumans() ?? '' }})</span>
    </td>
    <td class="px-4 py-2 text-center space-x-2">
        <a href="{{ route('verificaciones.edit', $verificacion->id_verificacion) }}" class="text-yellow-600 hover:text-yellow-800" aria-label="Editar">✏️</a>

        <button @click="openDelete = true" class="text-red-600 hover:text-red-800" aria-label="Eliminar">🗑️</button>

        <div x-show="openDelete" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white rounded-lg p-6 w-96" role="dialog" aria-modal="true" aria-labelledby="modal-title-{{ $verificacion->id_verificacion }}">
                <h2 id="modal-title-{{ $verificacion->id_verificacion }}" class="text-lg font-semibold mb-4">¿Eliminar verificación?</h2>
                <p class="mb-6">Esta acción no se puede deshacer.</p>
                <div class="flex justify-end gap-2">
                    <button @click="openDelete=false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                    <form action="{{ route('verificaciones.destroy', $verificacion->id_verificacion) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </td>
</tr>
