@props([
    'postulante' => null
])

<div x-data="dniLookup()" x-init="init()">

    {{-- TOAST --}}
    <div x-show="toast.show"
         x-transition
         class="fixed top-5 right-5 z-50 px-6 py-3 rounded-lg shadow-lg"
         :class="toast.type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'"
         x-text="toast.message"
         x-cloak>
    </div>

    {{-- DNI --}}
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">DNI</label>
        <input
            type="text"
            maxlength="8"
            x-model="dni"
            @input.debounce.500ms="buscarDni"
            class="w-full border rounded-lg px-3 py-2"
            required
        >
        {{-- Campo oculto para enviar el DNI al backend --}}
        <input type="hidden" name="dni" :value="dni">
    </div>

    {{-- NOMBRES --}}
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Nombres</label>
        <input
            type="text"
            name="nombres"
            x-model="nombres"
            class="w-full border rounded-lg px-3 py-2 bg-gray-100"
            readonly
            required
        >
    </div>

    {{-- APELLIDOS --}}
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Apellidos</label>
        <input
            type="text"
            name="apellidos"
            x-model="apellidos"
            class="w-full border rounded-lg px-3 py-2 bg-gray-100"
            readonly
            required
        >
    </div>

    {{-- TIPO LICENCIA --}}
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Tipo de Licencia</label>
        <select
            name="tipo_licencia"
            class="w-full border rounded-lg px-3 py-2"
            required
        >
            <option value="">-- Seleccione --</option>
            @php
                $tipos = ['A-I','A-IIa','A-IIb','A-IIIa','A-IIIb','A-IIIc'];
                $actual = old('tipo_licencia', $postulante?->procesoActivo?->tipo_licencia);
            @endphp
            @foreach($tipos as $tipo)
                <option value="{{ $tipo }}" @selected($actual === $tipo)>{{ $tipo }}</option>
            @endforeach
        </select>
    </div>

    {{-- FECHA PSICOSOMÁTICO --}}
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Fecha Psicosomático</label>
        <input
            type="date"
            name="fecha_psicosomatico"
            value="{{ old('fecha_psicosomatico', $postulante?->fecha_psicosomatico?->format('Y-m-d')) }}"
            class="w-full border rounded-lg px-3 py-2"
            required
        >
        <p class="text-xs text-gray-500 mt-1">Vigencia: 6 meses</p>
    </div>

</div>

<script>
function dniLookup() {
    return {
        dni: '{{ old("dni", $postulante->dni ?? "") }}',
        nombres: '{{ old("nombres", $postulante->nombres ?? "") }}',
        apellidos: '{{ old("apellidos", $postulante->apellidos ?? "") }}',
        toast: { show: false, message: '', type: 'success' },

        init() {},

        mostrarToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => this.toast.show = false, 3000);
        },

        buscarDni() {
            if (this.dni.length !== 8) return;

            fetch(`{{ route('postulantes.buscar-dni') }}?dni=${this.dni}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.error) {
                        this.nombres = data.nombres;
                        this.apellidos = data.apellidos;
                        this.mostrarToast('✅ DNI encontrado', 'success');
                    } else {
                        this.nombres = '';
                        this.apellidos = '';
                        this.mostrarToast('❌ DNI no encontrado', 'error');
                    }
                })
                .catch(err => {
                    this.nombres = '';
                    this.apellidos = '';
                    this.mostrarToast('❌ Error en la API', 'error');
                });
        }
    }
}
</script>
