<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Events\ContactoCreado;
use Illuminate\Http\Request;
use App\Jobs\EnviarWhatsappJob;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        \Log::info('👤 Usuario autenticado en /contacts:', ['user_id' => $user?->id]);
        $business = $user->business;
        \Log::info('🏢 Negocio del usuario:', ['business' => $business]);
        if (!$business) {
            return response()->json(['error' => 'No se pudo encontrar el negocio asociado.'], 403);
        }

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $contact = $business->contacts()->create($validated);

        // 🟢 Emitir el evento
        ContactoCreado::dispatch($contact);

        return response()->json([
            'message' => 'Contacto guardado y mensaje enviado.',
            'contact' => $contact,
        ], 201);
    }
    public function index(Request $request)
    {
        $user = $request->user();
    
        if (!$user->business) {
            return response()->json([]);
        }
    
        $contacts = Contact::where('business_id', $user->business->id)->latest()->get();
    
        return response()->json($contacts);
    }
}
