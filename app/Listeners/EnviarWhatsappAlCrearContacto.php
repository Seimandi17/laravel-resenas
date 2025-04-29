<?php

namespace App\Listeners;

use App\Events\ContactoCreado;
use App\Jobs\EnviarWhatsappJob;

class EnviarWhatsappAlCrearContacto
{
    public function handle(ContactoCreado $event): void
    {
        EnviarWhatsappJob::dispatch($event->contact);
    }
}
