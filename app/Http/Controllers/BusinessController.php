<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;

class BusinessController extends Controller
{
    // 1️⃣ Crear negocio sin autenticación (fase A)
    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:businesses,email',
            'phone'       => 'required|string',
            'category'    => 'required|string',
            'location'    => 'required|string',
            'description' => 'required|string',
        ]);

        $business = Business::create($validated);

        return response()->json([
            'message'  => 'Negocio registrado correctamente.',
            'business' => $business,
        ], 201);
    }

    // 2️⃣ Mostrar negocio del usuario autenticado (fase B)
    public function show(Request $request)
    {
        $user = $request->user();
    
        if ($user->business) {
            return response()->json([
                'name' => $user->business->name,
                'email' => $user->business->email,
                'phone' => $user->business->phone,
                'category' => $user->business->category,
                'description' => $user->business->description,
                // 👇 Agregamos campos del dueño
                'owner_name' => $user->name,
                'owner_email' => $user->email,
            ]);
        }
    
        return response()->json(null, 404);
    }

    // 3️⃣ Asociar negocio a usuario autenticado
    public function assignToUser(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
        ]);
    
        $user = $request->user();
        $business = Business::find($request->business_id);
    
        if ($business->user_id) {
            return response()->json(['message' => 'Este negocio ya está asignado.'], 409);
        }
    
        $business->user_id = $user->id;
        $business->save();
    
        return response()->json(['message' => 'Negocio asociado correctamente']);
    }
    public function store(Request $request)
{
    $user = auth()->user();

    // Validaciones básicas (opcional agregar más luego)
    $request->validate([
        'businessName' => 'required|string|max:255',
        'businessEmail' => 'required|email|max:255',
        'businessPhone' => 'required|string|max:255',
        'ownerName' => 'required|string|max:255',
        'ownerEmail' => 'required|email|max:255',
    ]);

    // Actualizar negocio
    $business = $user->business;
    $business->name = $request->businessName;
    $business->email = $request->businessEmail;
    $business->phone = $request->businessPhone;
    $business->save();

    // Actualizar dueño
    $user->name = $request->ownerName;
    $user->email = $request->ownerEmail;
    $user->save();

    return response()->json(['message' => 'Negocio y propietario actualizados exitosamente']);
}

    
}
