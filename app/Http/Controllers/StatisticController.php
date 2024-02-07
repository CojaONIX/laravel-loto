<?php

namespace App\Http\Controllers;

use App\Models\Round;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function index($round)
    {
        // Menu Buttons
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round');
        if($round == 'page') {
            return view('statistic', compact('rounds', 'round'));
        }

        $tickets = Ticket::where(['user_id' => Auth::id(), 'round' => $round])->get();
        $winNumbers = Round::select('numbers')->where(['round' => $round])->first();
        $winNumbers = $winNumbers ? $winNumbers->toArray() : ['numbers' => []];

        return view('statistic', compact('tickets', 'winNumbers', 'rounds', 'round'));
    }
}
