@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <h2 class="text-xl font-bold">Perfil de usuario</h2>

    {{-- Actualizar información --}}
    <div class="bg-white shadow rounded-lg p-6">
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Cambiar contraseña --}}
    <div class="bg-white shadow rounded-lg p-6">
        @include('profile.partials.update-password-form')
    </div>

    {{-- Eliminar cuenta --}}
    <div class="bg-white shadow rounded-lg p-6">
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection
