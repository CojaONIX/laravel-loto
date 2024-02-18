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
        $configLotto = config('loto');

        $report['ticketsCount'] = Ticket::where(['round' => $round])->count();
        $report['ticketsValue'] = $report['ticketsCount'] * $configLotto['combination']['price'];

        $report['bank']['percentage'] = $configLotto['bank'];
        $report['bank']['fund'] = $report['ticketsValue'] / 100 * $report['bank']['percentage'];

        $report['wins'] = $configLotto['wins'];
        foreach ($report['wins']['percentages'] as $win => $winPercentage)
        {
            $report['wins']['funds'][$win] = $report['ticketsValue'] / 100 * $winPercentage;
        }

        $lastRound = Round::latest('id')->first();
        $report['fundIN'] = $lastRound ? $lastRound->fundOUT : 0;
        $report['fundOUT'] = 0;

        if($counts)
        {
            foreach($report['wins']['funds'] as $win => $fund)
            {
                if(isset($counts[$win]))
                {
                    $report['wins']['counts'][$win] = $counts[$win];
                    $report['wins']['paids'][$win] = round($fund / $counts[$win], 2);
                }
                else
                {
                    $report['wins']['counts'][$win] = 0;
                    $report['wins']['paids'][$win] = 0;
                    $report['fundOUT'] += $fund;
                }
            }
        }

        return $report;
    }
}


// $report
//{
//    "ticketsCount": 2,
//    "ticketsValue": 200,
//    "bank": {
//      "percentage": 10,
//      "fund": 20
//  },
//  "wins": {
//        "percentages": {
//          "3": 20,
//          "4": 20,
//          "5": 20,
//          "6": 15,
//          "7": 15
//      },
//      "funds": {
//          "3": 40,
//          "4": 40,
//          "5": 40,
//          "6": 30,
//          "7": 30
//      },
//      "counts": {
//          "3": 1,
//          "4": 0,
//          "5": 0,
//          "6": 0,
//          "7": 0
//      },
//      "paids": {
//          "3": 40,
//          "4": 0,
//          "5": 0,
//          "6": 0,
//          "7": 0
//      }
//  },
//  "fundIN": 0,
//  "fundOUT": 140
//}
