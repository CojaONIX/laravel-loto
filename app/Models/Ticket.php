<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'credit_id',
        'year',
        'round',
        'n1',
        'n2',
        'n3',
        'n4',
        'n5',
        'winning',
        'paid'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }
}
