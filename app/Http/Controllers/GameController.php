<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lotto;
use Illuminate\Support\Str;

use App\Services\NextRoundClass;

class GameController extends Controller
{
    public function index()
    {
        $creditsSum = Credit::where('user_id', Auth::id())->sum('amount');
        $nextRound = new NextRoundClass();
        $tickets = User::find(Auth::id())->tickets()->select('numbers')->where(['round' => $nextRound->formated])->get();
        $isPlayed = Round::select('created_at')->where('round', $nextRound->formated)->first();

        return view('game', compact('creditsSum', 'nextRound', 'tickets', 'isPlayed'));
    }

    public function addTicket(Request $request)
    {
        $nextRound = new NextRoundClass();
        $round = $nextRound->formated;
        $combination = config('loto.combination');

        $userCantPlayRound = Lotto::userCantPlayRound($round, $combination);
        if($userCantPlayRound)
        {
            return redirect()->route('game.view')->withErrors($userCantPlayRound);
        }


        Credit::create([
            'user_id' => Auth::id(),
            'type' => Credit::TYPE_BET,
            'amount' => -$combination['price']
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'round' => $round,
            'numbers' => Lotto::getRandomCombination($combination)
        ]);

        return redirect()->route('game.view');
    }

    public function addCustomTicket(Request $request)
    {
        $combination = config('loto.combination');

        $numbers = json_decode($request->combination);

        if(!$numbers)
        {
            return redirect()->route('game.view')->withErrors('Wrong request.');
        }

        $numbers = array_unique($numbers);
        if(!$numbers or count($numbers) != $combination['find'])
        {
            return redirect()->route('game.view')->withErrors('Wrong count of numbers.');
        }

        $nextRound = new NextRoundClass();
        $round = $nextRound->formated;

        $userCantPlayRound = Lotto::userCantPlayRound($round, $combination);
        if($userCantPlayRound)
        {
            return redirect()->route('game.view')->withErrors($userCantPlayRound);
        }


        Credit::create([
            'user_id' => Auth::id(),
            'type' => Credit::TYPE_BET,
            'amount' => -$combination['price']
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'round' => $round,
            'numbers' => $numbers
        ]);

        return redirect()->route('game.view');
    }

}
