{{-- MODAL CREAR POSTULANTE --}}
<div
    x-show="openCreate"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    style="display: none"
>

    <div
        @click.away="openCreate = false"
        class="bg-white w-full max-w-lg rounded-xl shadow-lg"
    >

        {{-- CABECERA --}}
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                ➕ Nuevo Postulante
            </h2>

            <button
                @click="openCreate = false"
                class="text-gray-500 hover:text-gray-700 text-xl"
            >
                ✕
            </button>
        </div>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="mx-6 mt-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('postulantes.store') }}"
            class="p-6 space-y-4"
        >
            @csrf

            @include('postulantes.form-alpine') {{-- Aquí incluimos el formulario con toast y autocompletado --}}

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button
                    type="button"
                    @click="openCreate = false"
                    class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700"
                >
                    💾 Guardar Postulante
                </button>
            </div>
        </form>

    </div>
</div>
