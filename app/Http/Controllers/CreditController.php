<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    public function homeKredit()
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
        return view('home', compact('credits', 'round', 'date', 'ticketsNumber'));
    }

    public function uplataKredita(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 0,
            'amount' => $request->get('amount')
        ]);

        return redirect()->route('kredit.home');
    }

    public function isplataSaKredita(Request $request)
    {
        if(Credit::where('user_id', Auth::id())->sum('amount') < $request->get('amount'))
        {
            return redirect()->route('kredit.home')->withErrors(['message'=>'Nemate dovoljno kredita za isplatu trazenog iznosa!']);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 1,
            'amount' => -$request->get('amount')
        ]);

        return redirect()->route('kredit.home');
    }

    public function uplataTiketa(Request $request)
    {
        if(Credit::where('user_id', Auth::id())->sum('amount') < 100)
        {
            return redirect()->route('kredit.home')->withErrors(['message'=>'Nemate dovoljno kredita za uplatu tiketa!']);
        }

        if(Credit::where(['user_id' => Auth::id(), 'type' => 2])->count() >= 50)
        {
            return redirect()->route('kredit.home')->withErrors(['message'=>'Ne mozete uplatiti vise od 5 tiketa po kolu!']);
        }

        $numbers = Arr::random(range(0, 9), 5);

        $credit = Credit::create([
            'user_id' => Auth::id(),
            'type' => 2,
            'amount' => -100
        ]);

        Ticket::create([
            'credit_id' => $credit->id,
            'year' => Carbon::now()->year,
            'round' => 8,
            'numbers' => $numbers,
            'winning' => 0,
            'paid' => false
        ]);

        return redirect()->route('kredit.home');
    }

    public function isplataDobitka(Request $request)
    {
       $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 3,
            'amount' => $request->get('amount')
        ]);

        return redirect()->route('kredit.home');
    }
}
