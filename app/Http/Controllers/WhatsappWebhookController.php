<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Business;

class WhatsappWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Log::info('📩 Webhook recibido', [
            'payload' => $request->all(),
            'ip' => $request->ip(),
        ]);

        $fromRaw = $request->input('data.from'); // Ej: "5492954677479@c.us"
        $from = '+' . preg_replace('/[^0-9]/', '', explode('@', $fromRaw)[0]); // Limpio: "5492954677479"

        \Log::info('🔍 Buscando contacto con número limpio', ['cleanPhone' => $from]);

        $body = strtolower(trim($request->input('data.body')));
        $instanceId = config('services.ultramsg.instance_id');
        $token = config('services.ultramsg.token');

        $contact = Contact::where('client_phone', $from)->latest()->first();

        if (! $contact) {
            \Log::warning('❌ Contacto no encontrado para número', ['from' => $from]);
            return response()->json(['status' => 'contact not found'], 404);
        }

        $positiveResponses = [
            '👍', 'si', 'sí', 'perfecto', 'genial', 'bueno', 'ok', 'okey',
            'Sí', 'Si', 'Perfecto', 'Genial', 'Bueno', 'Ok', 'Okey'
        ];

        $negativeResponses = ['👎', 'no', 'mal', 'regular', 'No', 'Mal', 'Regular'];

        if (in_array($body, array_map('strtolower', $positiveResponses))) {
            $business = Business::find($contact->business_id);
            $link = $business?->location ?? 'https://www.google.com/maps';

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
        } elseif (in_array($body, array_map('strtolower', $negativeResponses))) {
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
