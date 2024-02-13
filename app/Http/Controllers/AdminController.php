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
        $roundActive = $round;
        $round = Round::where(['round' => $round])->first();

        $numbers = $round ? $round->numbers : [];
        $report = $round ? $round->report : Lotto::getRoundReport($roundActive);
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round'); //buttons

        return view('adminRound', compact('report', 'rounds', 'roundActive', 'numbers'));
    }

    public function rollNumbers(Request $request)
    {
        $round = $request->get('round');
        $configLotto = config('loto');
        $numbers = Lotto::getRandomCombination($configLotto['combination']);

        $tickets = Ticket::where(['round' => $round])->get();
        $winTickets = array();
        $minWin = min(array_keys($configLotto['wins']['percentages']));
        foreach($tickets as $ticket)
        {
            $win = count(array_intersect($numbers, $ticket->numbers));
            if($win >= $minWin)
            {
                $winTickets[] = [
                    'user_id' => $ticket->user_id,
                    'type' => 3,
                    'amount' => $win
                ];
            }
        }

        $counts = array_count_values(array_column($winTickets, 'amount'));
        $report = Lotto::getRoundReport($round, $counts);

        $time = Round::create([
            'round' => $round,
            'numbers' => $numbers,
            'report' => $report,
            'bank' => $report['bank']['fund'],
            'fundIN' => $report['fundIN'],
            'fundOUT' => $report['fundOUT']
        ])->created_at;

        $paids = $report['wins']['paids'];
        foreach($winTickets as &$winTicket)
        {
            $winTicket['amount'] = $paids[$winTicket['amount']];
            $winTicket['created_at'] = $time;
            $winTicket['updated_at'] = $time;
        }

        Credit::insert($winTickets);

        return redirect()->route('admin.round.view', ['round' => $round]);
    }

}
