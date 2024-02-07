<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $creditsSum = Credit::where('user_id', Auth::id())->sum('amount');

        $nextRound = Ticket::nextRound();
        $round = $nextRound['date']->year . '-' . str_pad($nextRound['round'], 4, "0", STR_PAD_LEFT);

        $isPlayed = Round::select('created_at')->where('round', $round)->first();

        $tickets = User::find(Auth::id())->tickets()->select('numbers')->where(['round' => $round])->get();
        return view('game', compact('creditsSum', 'nextRound', 'tickets', 'isPlayed'));
    }


    public function addTicket(Request $request)
    {
        $nextRound = Ticket::nextRound();

        if(Credit::where('user_id', Auth::id())->sum('amount') < 100)
        {
            return redirect()->route('game.view')->withErrors(['message'=>'Nemate dovoljno kredita za uplatu tiketa!']);
        }

        if(Credit::where(['user_id' => Auth::id(), 'type' => 2, 'round' => $nextRound['round']])->count() >= 50)
        {
            return redirect()->route('game.view')->withErrors(['message'=>'Ne mozete uplatiti vise od 50 tiketa po kolu!']);
        }

        $numbers = Arr::random(range(1, 10), 5);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 2,
            'amount' => -100
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'round' => $nextRound['date']->year . '-' . str_pad($nextRound['round'], 4, "0", STR_PAD_LEFT),
            'numbers' => $numbers
        ]);

        return redirect()->route('game.view');
    }

}
