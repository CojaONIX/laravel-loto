<?php

namespace App\Services;

use Carbon\Carbon;

class LottoService
{
    public static function nextRound(): array
    {
        $roundStartsAt = config('loto.round'); // /config/loto.php

        $firstRound = new Carbon( "first {$roundStartsAt['day']} of January");
        $firstRound->addHours($roundStartsAt['hour'])->subHour()->addMinutes($roundStartsAt['minute']);
        $round = $firstRound->diffInWeeks(Carbon::now()) + 1 + 1;
        $date = $firstRound->addWeeks($round - 1)->addHour();

        return [
            'round' => $round,
            'date' => $date,
            'year-round' => $date->year . '-' . str_pad($round, 4, "0", STR_PAD_LEFT)
        ];
    }
}
