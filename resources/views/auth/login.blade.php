<x-guest-layout>
    
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-800">Iniciar Sesión</h2>
                <p class="mt-2 text-sm text-gray-500">Accede a tu cuenta para administrar el sistema</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div class="relative">
                    <label for="email" class="sr-only">Email</label>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 12H8m0 0l4-4m-4 4l4 4"/>
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" required autofocus
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                           placeholder="Correo electrónico">
                </div>

                {{-- Password --}}
                <div class="relative">
                    <label for="password" class="sr-only">Contraseña</label>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 11c0-1.1.9-2 2-2s2 .9 2 2v3h-4v-3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 12V7a7 7 0 0114 0v5"/>
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" required
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                           placeholder="Contraseña">
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-400 transition">
                        Entrar
                    </button>
                </div>

                {{-- Opcionales: olvidé contraseña / registro --}}
                

                {{-- Error messages --}}
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        
</x-guest-layout>
