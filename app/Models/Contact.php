<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'client_name',
        'client_phone',
        'message',
        'contacted_at', // opcional, si usás este campo
    ];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}