<?php

namespace App\Services;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Arr;
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

    public static function getRandomCombination($combination)
    {
        return Arr::random(range(1, $combination['from']), $combination['find']);
    }

    public static function getRoundReport($round, $counts=null)
    {
        $report = Round::where(['round' => $round])->first();

        if($report)
        {
            return $report->report;
        }

        $lastRound = Round::latest('id')->first();
        $report['fundIN'] = $lastRound ? $lastRound->fundOUT : 0;
        $report['fundOUT'] = 0;

        $configLotto = config('loto');

        $ticketsCount = Ticket::where(['round' => $round])->count();
        $ticketsValue = $ticketsCount * $configLotto['combination']['price'];

        $report['ticketsCount'] = $ticketsCount;
        $report['ticketsValue'] = $ticketsValue;

        $bankPercentage = $configLotto['bank'];
        $report['bank']['percentage'] = $bankPercentage;
        $report['bank']['fund'] = $ticketsValue / 100 * $bankPercentage;

        $report['wins'] = $configLotto['wins'];
        foreach ($report['wins']['percentages'] as $win => $winPercentage)
        {
            $report['wins']['funds'][$win] = $ticketsValue / 100 * $winPercentage;

            if($win == max(array_keys($report['wins']['percentages'])))
            {
                $report['wins']['funds'][$win] += $report['fundIN'];
            }
        }

        if($counts)
        {
            $report['wins']['counts'] = $counts;

            foreach($counts as $win => $count)
            {
                if($count > 0)
                {
                    $paids[$win] = round($report['wins']['funds'][$win] / $count, 2);
                }
                else
                {
                    $paids[$win] = 0;
                    $report['fundOUT'] += $report['wins']['funds'][$win];
                }
            }

            $report['wins']['paids'] = $paids;

        }


        return $report;
    }
}
