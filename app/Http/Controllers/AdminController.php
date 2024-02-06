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
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round');
        if($round == 'page') {
            return view('admin', compact('rounds', 'round'));
        }
        $report['rounds'] = $rounds;

        $ticketsCount = Ticket::where(['round' => $round])->count();
        $ticketsValue = $ticketsCount * 100;

        $report['round'] = $round;
        $report['ticketsCount'] = Ticket::where(['round' => $round])->count();
        $report['ticketsValue'] = $report['ticketsCount'] * 100;

        $b = config('loto.bank');
        $report['bank']['percentage'] = $b;
        $report['bank']['value'] = $ticketsValue / 100 * $b;

        $funds = config('loto.funds');
        foreach ($funds as $k => $v)
        {
            $report['wins'][$k]['percentage'] = $v;
            $report['wins'][$k]['value'] = $ticketsValue / 100 * $v;
        }

        $report['played'] = Round::where(['round' => $round])->select('created_at')->first();

        $lastRound = Round::latest('id')->first();
        $transfer = $lastRound ? $lastRound->transfer : 0;
        $report['transfer'] = 1000; //$transfer;

        return view('admin', compact('report'));
    }

    public function rollNumbers(Request $request)
    {
        $lastRound = Round::latest('id')->first();
        $transfer = $lastRound ? $lastRound->transfer : 0; // vrednost prenetog fonda iz prethodnog kola

        $tickets = Ticket::where(['round' => $request->get('round')])->get();
        $numbers = Arr::random(range(1, 10), 5);
        foreach($tickets as $ticket)
        {
            $ticket->win = count(array_intersect($numbers, $ticket->numbers));
        }

        $wins = array_count_values($tickets->pluck('win')->toArray());
        $paids = config('loto.funds');
        foreach($paids as $k => $v)
        {
            $paids[$k] = (count($tickets) * 100) / 100 * $v; // fond dobitka

            if($k == max(array_keys($paids)))
            {
                $paids[$k] += $transfer; // transfer prenetog fonda maksimalnom dobitku
                $transfer = 0;
            }

            if(isset($wins[$k]))
            {
                $paids[$k] = number_format($paids[$k] / $wins[$k], 2); // vrednost dobitka ako postoji
            }
            else
            {
                $transfer += $paids[$k]; // prenosni fond u sledece kolo ako dobitak ne postoji
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

//        Round::create([
//            'round' => $request->get('round'),
//            'numbers' => $numbers,
//            'bank' => $request->get('bank'),
//            'transfer' => $transfer
//        ]);

        return redirect()->route('admin.view', ['round' => $request->get('round')]);
    }

}
