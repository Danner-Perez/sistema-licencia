<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Mostrar todos los usuarios
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Crear usuario (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6',
            'rol'=>'required|in:admin,examinador,asistencia'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'rol'=>$request->rol
        ]);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    // Editar usuario (AJAX) se gestiona con index + modal, no se necesita vista separada

    // Actualizar usuario (AJAX)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'nullable|string|min:6',
            'rol'=>'required|in:admin,examinador,asistencia'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->rol = $request->rol;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    // Eliminar usuario (AJAX)
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
