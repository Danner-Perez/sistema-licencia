@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="usersData()">
    <h1 class="text-2xl font-bold mb-4">Administrar Usuarios</h1>

    {{-- Botón crear --}}
    <button @click="openCreateModal = true"
            class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Crear Usuario
    </button>

    {{-- Tabla usuarios --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="user in users" :key="user.id">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="user.id"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="user.name"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="user.email"></td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize" x-text="user.rol"></td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <button @click="editUser(user)"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Editar
                            </button>
                            <button @click="deleteUser(user.id)"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Modal Crear / Editar --}}
    <div x-show="openCreateModal || openEditModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div @click.away="closeModals()"
             class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

            <h2 class="text-lg font-semibold mb-4" x-text="modalTitle"></h2>

            <form @submit.prevent="submitForm()">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" x-model="form.name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" x-model="form.email" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Contraseña</label>
                    <input type="password" x-model="form.password" class="w-full border rounded px-3 py-2" :required="openCreateModal">
                    <span class="text-xs text-gray-500" x-show="openEditModal">Dejar vacío si no desea cambiar</span>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Rol</label>
                    <select x-model="form.rol" class="w-full border rounded px-3 py-2" required>
                        <option value="admin">Administrador</option>
                        <option value="examinador">Examinador</option>
                        <option value="asistencia">Asistencia</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="closeModals()" class="px-4 py-2 rounded border">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function usersData() {
    return {
        users: @json($users),
        openCreateModal: false,
        openEditModal: false,
        modalTitle: '',
        form: { id: null, name: '', email: '', password: '', rol: 'admin' },

        editUser(user) {
            this.modalTitle = 'Editar Usuario';
            this.form.id = user.id;
            this.form.name = user.name;
            this.form.email = user.email;
            this.form.password = '';
            this.form.rol = user.rol;
            this.openEditModal = true;
        },

        closeModals() {
            this.openCreateModal = false;
            this.openEditModal = false;
            this.form = { id: null, name: '', email: '', password: '', rol: 'admin' };
        },

        submitForm() {
            const url = this.openCreateModal
                ? '{{ route("admin.users.store") }}'
                : '{{ url("admin/users") }}/' + this.form.id;

            const method = this.openCreateModal ? 'POST' : 'PUT';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.form)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if(this.openCreateModal) this.users.push(data.user);
                    else {
                        const index = this.users.findIndex(u => u.id === data.user.id);
                        if(index !== -1) this.users[index] = data.user;
                    }
                    this.closeModals();
                }
            })
            .catch(err => console.error(err));
        },

        deleteUser(id) {
            if(!confirm('¿Desea eliminar este usuario?')) return;

            fetch('{{ url("admin/users") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.users = this.users.filter(u => u.id !== id);
                }
            })
            .catch(err => console.error(err));
        }
    }
}
</script>
@endsection
