{{-- MODAL EDITAR POSTULANTE --}}
<div
    x-show="openEdit"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    style="display: none"
>
    <div
        @click.away="openEdit = false"
        class="bg-white w-full max-w-lg rounded-xl shadow-lg"
    >

        {{-- CABECERA --}}
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                ✏️ Editar Postulante
            </h2>

            <button
                @click="openEdit = false"
                class="text-gray-500 hover:text-gray-700 text-xl"
            >
                ✕
            </button>
        </div>

        {{-- FORM --}}
        <form
            method="POST"
            :action="`/postulantes/${postulante.id}`"
            class="p-6 space-y-4"
        >
            @csrf
            @method('PUT')

            {{-- DNI --}}
            <div>
                <label class="block text-sm font-medium mb-1">DNI</label>
                <input
                    type="text"
                    x-model="postulante.dni"
                    disabled
                    class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                >
            </div>

            {{-- NOMBRES --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nombres</label>
                <input
                    type="text"
                    name="nombres"
                    x-model="postulante.nombres"
                    class="w-full border rounded-lg px-3 py-2"
                    required
                >
            </div>

            {{-- APELLIDOS --}}
            <div>
                <label class="block text-sm font-medium mb-1">Apellidos</label>
                <input
                    type="text"
                    name="apellidos"
                    x-model="postulante.apellidos"
                    class="w-full border rounded-lg px-3 py-2"
                    required
                >
            </div>

            {{-- TIPO LICENCIA (CORREGIDO) --}}
            <div>
                <label class="block text-sm font-medium mb-1">Tipo de Licencia</label>
                <select
                    name="tipo_licencia"
                    x-model="postulante.tipo_licencia"
                    class="w-full border rounded-lg px-3 py-2"
                    required
                >
                    <option value="">-- Seleccione --</option>
                    <option value="A-I">A-I</option>
                    <option value="A-IIa">A-IIa</option>
                    <option value="A-IIb">A-IIb</option>
                    <option value="A-IIIa">A-IIIa</option>
                    <option value="A-IIIb">A-IIIb</option>
                    <option value="A-IIIc">A-IIIc</option>
                </select>
            </div>

            {{-- FECHA PSICOSOMÁTICO --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Fecha Psicosomático
                </label>
                <input
                    type="date"
                    name="fecha_psicosomatico"
                    x-model="postulante.fecha_psicosomatico"
                    class="w-full border rounded-lg px-3 py-2"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">
                    Vigencia: 6 meses
                </p>
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button
                    type="button"
                    @click="openEdit = false"
                    class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600"
                >
                    💾 Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>
