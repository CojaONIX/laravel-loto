<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'year',
        'round',
        'numbers',
        'winning',
        'paid'
    ];

    protected $casts = [
        'numbers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
