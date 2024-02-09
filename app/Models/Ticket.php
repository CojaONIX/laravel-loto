<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'round',
        'numbers',
        'win',
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
