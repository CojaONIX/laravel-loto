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
        $winNumbers = Round::select('numbers')->where(['round' => $round])->first();
        $winNumbers = $winNumbers ? $winNumbers['numbers'] : [];

        return view('statisticRound', compact('tickets', 'winNumbers', 'rounds', 'roundActive'));
    }
}
