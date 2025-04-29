<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Contact;
use App\Models\Message;

class WhatsappWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $from = $request->input('from');  // ej: "+34600111222"
        $body = strtolower(trim($request->input('body'))); // mensaje que escribió el cliente
        $instanceId = config('services.ultramsg.instance_id');
        $token = config('services.ultramsg.token');

        // Buscar el último contacto
        $contact = Contact::where('client_phone', $from)->latest()->first();

        if (! $contact) {
            return response()->json(['status' => 'contact not found'], 404);
        }

        if (in_array($body, ['👍', 'si', 'sí', 'perfecto', 'genial'])) {
            $link = 'https://www.google.com/maps/place/Negocio+De+Prueba'; // personalizá esto

            Http::post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
                'token' => $token,
                'to' => $from,
                'body' => "¡Nos alegra saberlo! ¿Podés dejarnos una reseña? $link",
            ]);

            $contact->update(['contacted_at' => now()]);

            Message::create([
                'contact_id' => $contact->id,
                'business_id' => $contact->business_id,
                'message_text' => "Respuesta automática con link de reseña enviada",
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } elseif (in_array($body, ['👎', 'no', 'mal', 'regular'])) {
            Http::post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
                'token' => $token,
                'to' => $from,
                'body' => "Lamentamos que tu experiencia no haya sido la mejor. ¡Gracias por tu sinceridad!",
            ]);

            $contact->update(['contacted_at' => now()]);

            Message::create([
                'contact_id' => $contact->id,
                'business_id' => $contact->business_id,
                'message_text' => "Respuesta empática enviada tras reseña negativa",
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}

