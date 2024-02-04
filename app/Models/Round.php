<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $table = 'rounds';

    protected $fillable = [
        'round',
        'numbers',
        'bank'
    ];

    protected $casts = [
        'numbers' => 'array',
    ];
}
