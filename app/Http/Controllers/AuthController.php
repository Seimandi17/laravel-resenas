<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registro
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Usuario registrado correctamente.',
            'user'    => $user,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }
    
        $user->load('business'); // 👈🏽 ESTA LÍNEA AGREGA LA RELACIÓN
    
        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'token'   => $user->createToken('auth_token')->plainTextToken,
            'user'    => $user,
        ]);
    }

    // Obtener el usuario autenticado
   public function user(Request $request)
{
    $user = $request->user();
    $user->load('business');

    return response()->json([
        'user' => $user,
        'token' => $request->bearerToken(), // opcional
    ]);
}
    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }
}
