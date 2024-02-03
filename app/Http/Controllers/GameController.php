<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $credits = Credit::where('user_id', Auth::id())->get();

        $roundStartsAt = [
            'day' => 'Thursday', // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday
            'hour' => 17,
            'minute' => 18
        ];

        $firstRound = new Carbon( "first {$roundStartsAt['day']} of January");
        $firstRound->addHours($roundStartsAt['hour'])->subHour()->addMinutes($roundStartsAt['minute']);
        $round = $firstRound->diffInWeeks(Carbon::now()) + 1 + 1;

        $date = $firstRound->addWeeks($round - 1)->addHour();

        $ticketsNumber = Credit::where(['user_id' => Auth::id(), 'type' => 2])->count();
        return view('game', compact('credits', 'round', 'date', 'ticketsNumber'));
    }


    public function addTicket(Request $request)
    {
        if(Credit::where('user_id', Auth::id())->sum('amount') < 100)
        {
            return redirect()->route('game.view')->withErrors(['message'=>'Nemate dovoljno kredita za uplatu tiketa!']);
        }

        if(Credit::where(['user_id' => Auth::id(), 'type' => 2])->count() >= 50)
        {
            return redirect()->route('game.view')->withErrors(['message'=>'Ne mozete uplatiti vise od 50 tiketa po kolu!']);
        }

        $numbers = Arr::random(range(0, 9), 5);


        $ticket = Ticket::create([
            'year' => Carbon::now()->year,
            'round' => 8,
            'numbers' => $numbers,
            'winning' => 0,
            'paid' => false
        ]);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 2,
            'amount' => -100,
            'ticket_id' => $ticket->id
        ]);


        return redirect()->route('game.view');
    }

}
