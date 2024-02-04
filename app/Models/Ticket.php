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

    public static function nextRound()
    {
        $roundStartsAt = config('loto.round'); // /config/loto.php

        $firstRound = new Carbon( "first {$roundStartsAt['day']} of January");
        $firstRound->addHours($roundStartsAt['hour'])->subHour()->addMinutes($roundStartsAt['minute']);
        $round = $firstRound->diffInWeeks(Carbon::now()) + 1 + 1;
        $date = $firstRound->addWeeks($round - 1)->addHour();

        return [
            'round' => $round,
            'date' => $date
        ];
    }

}
