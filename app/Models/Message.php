<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'contact_id',
        'business_id',
        'message_text',
        'status',
        'sent_at',
        'error',
        'link_sent',
    ];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
