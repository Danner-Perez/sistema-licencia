<x-guest-layout>
    <h2 class="text-2xl font-bold text-center mb-6">
        Iniciar Sesión
    </h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required
                   class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" name="password" required
                   class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
            Entrar
        </button>
    </form>
</x-guest-layout>
