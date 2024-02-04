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
        $creditsSum = Credit::where('user_id', Auth::id())->sum('amount');

        $roundStartsAt = config('loto.round');  // /config/loto.php

        $firstRound = new Carbon( "first {$roundStartsAt['day']} of January");
        $firstRound->addHours($roundStartsAt['hour'])->subHour()->addMinutes($roundStartsAt['minute']);
        $round = $firstRound->diffInWeeks(Carbon::now()) + 1 + 1;

        $date = $firstRound->addWeeks($round - 1)->addHour();

        $ticketsNumber = Credit::where(['user_id' => Auth::id(), 'type' => 2])->count();
        return view('game', compact('creditsSum', 'round', 'date', 'ticketsNumber'));
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

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 2,
            'amount' => -100
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'year' => Carbon::now()->year,
            'round' => 8,
            'numbers' => $numbers,
            'winning' => 0,
            'paid' => false
        ]);

        return redirect()->route('game.view');
    }

}
