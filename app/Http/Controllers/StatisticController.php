<?php

namespace App\Http\Controllers;

use App\Models\Round;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function index()
    {
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round'); // buttons
        return view('statistic', compact('rounds'));
    }

    public function roundStatistic($round)
    {
        $roundActive = $round;
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round'); // buttons

        $tickets = Ticket::where(['user_id' => Auth::id(), 'round' => $round])->get();
        $round = Round::where(['round' => $round])->first();
        $winNumbers = $round ? $round['numbers'] : [];

        if($round)
        {
            $paids = $round->report['wins']['paids'];

            foreach ($tickets as $ticket) {
                $win = count(array_intersect($winNumbers, $ticket->numbers));
                $ticket->win = $win;
                $ticket->paid = isset($paids[$win]) ? $paids[$win] : 0;
            }
        }

        return view('statisticRound', compact('tickets', 'winNumbers', 'rounds', 'roundActive'));
    }
}
