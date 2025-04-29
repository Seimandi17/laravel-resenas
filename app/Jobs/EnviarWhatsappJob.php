<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contact;
    protected $instanceId;
    protected $token;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
        $this->instanceId = config('services.ultramsg.instance_id'); // ✅ correcto para jobs
        $this->token = config('services.ultramsg.token');
    }

    public function handle(): void
    {
        $phone = $this->contact->client_phone;
        $nombre = $this->contact->client_name;
        $businessId = $this->contact->business_id;

        $mensaje = "Hola {$nombre}! Gracias por tu visita. ¿Cómo fue tu experiencia? Respondé con 👍 o 👎.";

        try {
            $response = Http::post("https://api.ultramsg.com/{$this->instanceId}/messages/chat", [
                'token' => $this->token,
                'to' => $phone,
                'body' => $mensaje,
            ]);

            $ok = $response->json()['sent'] ?? false;

            Message::create([
                'contact_id' => $this->contact->id,
                'business_id' => $businessId,
                'message_text' => $mensaje,
                'status' => $ok ? 'sent' : 'failed',
                'sent_at' => now(),
                'error' => $ok ? null : json_encode($response->json()),
            ]);
        } catch (\Exception $e) {
            Message::create([
                'contact_id' => $this->contact->id,
                'business_id' => $businessId,
                'message_text' => $mensaje,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
