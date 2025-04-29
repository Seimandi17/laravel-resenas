<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'payment_email',
        'payment_method',
        'amount',
        'start_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
