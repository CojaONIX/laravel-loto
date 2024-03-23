<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credits';

    const TYPE_PUSH = 0;
    const TYPE_PULL = 1;
    const TYPE_BET = 2;
    const TYPE_WIN = 3;

    const TYPES = ['push', 'pull', 'bet', 'win'];

    protected $fillable = [
        'user_id',
        'type',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
