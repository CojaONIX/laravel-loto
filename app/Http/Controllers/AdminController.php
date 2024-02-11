<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Lotto;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round');
        return view('admin', compact('rounds'));
    }

    public function roundReport($round)
    {
        $report = Lotto::getRoundReport($round);
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round'); //buttons
        return view('adminRound', compact('report', 'rounds', 'round'));
    }

    public function rollNumbers(Request $request)
    {
        $round = $request->get('round');

        $configLotto = config('loto');
        foreach(array_keys($configLotto['wins']['percentages']) as $win)
        {
            $counts[$win] = 0;
        }

        $numbers = Lotto::getRandomCombination($configLotto['combination']);

        $tickets = Ticket::where(['round' => $round])->get();
        foreach($tickets as $ticket)
        {
            $win = count(array_intersect($numbers, $ticket->numbers));
            if(isset($counts[$win]))
            {
                $ticket->win = $win;
                $counts[$win]++;
            }
        }

        $report = Lotto::getRoundReport($round, $counts);

        foreach($tickets as $ticket)
        {
            if($ticket->win) // Isplata dobitnicima
            {
                Credit::create([
                    'user_id' => $ticket->user_id,
                    'type' => 3,
                    'amount' => $ticket->win * 222
                ]);
            }
        }

        Round::create([
            'round' => $round,
            'numbers' => $numbers,
            'report' => $report,
            'bank' => $report['bank']['fund'],
            'fundIN' => $report['fundIN'],
            'fundOUT' => $report['fundOUT']
        ]);

        return redirect()->route('admin.view', ['round' => $round]);
    }

}
