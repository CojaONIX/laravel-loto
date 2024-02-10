<?php

namespace App\Services;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LottoService
{
    public static function nextRound()
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

    public static function userCantPlayRound($round, $combination)
    {
        $errors = array();
        if(Credit::where('user_id', Auth::id())->sum('amount') < $combination['price'])
        {
            $errors[] = 'Nemate dovoljno kredita za uplatu tiketa!';
        }

        if(Ticket::where(['user_id' => Auth::id(), 'round' => $round])->count() >= $combination['maxCount'])
        {
            $errors[] = 'Ne mozete uplatiti vise od '.$combination['maxCount'].' tiketa po kolu!';
        }

        if(Round::select('created_at')->where('round', $round)->first())
        {
            $errors[] = 'Ne mozete uplatiti tiket - kolo je odigrano!';
        }

        return $errors;
    }
}
