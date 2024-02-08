<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index($round)
    {
        // Menu Buttons
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round');
        if($round == 'page') {
            return view('admin', compact('rounds', 'round'));
        }

        $combination = config('loto.combination');
        $ticketsCount = Ticket::where(['round' => $round])->count();
        $ticketsValue = $ticketsCount * $combination['price'];

        $report['ticketsCount'] = $ticketsCount;
        $report['ticketsValue'] = $ticketsValue;

        $b = config('loto.bank');
        $report['bank']['percentage'] = $b;
        $report['bank']['value'] = $ticketsValue / 100 * $b;

        $funds = config('loto.funds');
        foreach ($funds as $k => $v)
        {
            $report['wins'][$k]['percentage'] = $v;
            $report['wins'][$k]['value'] = $ticketsValue / 100 * $v;
        }

        $report['played'] = Round::where(['round' => $round])->first();

        if($report['played'])
        {
            $report['fundIN'] = $report['played']['fundIN'];
            $report['fundOUT'] = $report['played']['fundOUT'];
        }
        else
        {
            $lastRound = Round::latest('id')->first();
            $report['fundIN'] = $lastRound ? $lastRound->fundOUT : 0;
            $report['fundOUT'] = null;
        }

        return view('admin', compact('report', 'rounds', 'round'));
    }

    public function rollNumbers(Request $request)
    {
        $combination = config('loto.combination');

        $lastRound = Round::latest('id')->first();
        $fundIN = $lastRound ? $lastRound->fundOUT : 0; // vrednost prenetog fonda iz prethodnog kola

        $tickets = Ticket::where(['round' => $request->get('round')])->get();
        $ticketsValue = count($tickets) * $combination['price'];

        $numbers = Arr::random(range(1, $combination['from']), $combination['find']);
        foreach($tickets as $ticket)
        {
            $ticket->win = count(array_intersect($numbers, $ticket->numbers));
        }

        $wins = array_count_values($tickets->pluck('win')->toArray());
        $paids = config('loto.funds');
        $fundOUT = 0;
        foreach($paids as $k => $v)
        {
            $paids[$k] = $ticketsValue / 100 * $v; // fond dobitka

            if($k == max(array_keys($paids)))
            {
                $paids[$k] += $fundIN; // transfer prenetog fonda maksimalnom dobitku
            }

            if(isset($wins[$k]))
            {
                $paids[$k] = round($paids[$k] / $wins[$k], 2); // vrednost dobitka ako postoji
                $report[$k]['wins'] = $wins[$k];
                $report[$k]['value'] = $paids[$k];
            }
            else
            {
                $fundOUT += $paids[$k]; // prenosni fond u sledece kolo ako dobitak ne postoji
                $report[$k]['wins'] = 0;
                $report[$k]['value'] = 0;
            }
        }

        foreach($tickets as $ticket)
        {
            $ticket->paid = isset($paids[$ticket->win]) ? $paids[$ticket->win] : 0;
            $ticket->save();

            if($ticket->paid > 0) { // Isplata dobitnicima
                Credit::create([
                    'user_id' => $ticket->user_id,
                    'type' => 3,
                    'amount' => $ticket->paid
                ]);
            }
        }

        Round::create([
            'round' => $request->get('round'),
            'numbers' => $numbers,
            'report' => $report,
            'bank' => $ticketsValue / 100 * config('loto.bank'),
            'fundIN' => $fundIN,
            'fundOUT' => $fundOUT
        ]);

        return redirect()->route('admin.view', ['round' => $request->get('round')]);
    }

}
