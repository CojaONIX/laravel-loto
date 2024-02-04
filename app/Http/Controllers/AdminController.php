<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index($round)
    {
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round');
        if($round == 'page') {
            return view('admin', compact('rounds', 'round'));
        }

        $ticketsCount = Ticket::where(['round' => $round])->count();
        $ticketsValue = $ticketsCount * 100;
        $quotas = config('loto.quotas');
        $isRoundOld = Round::where(['round' => $round])->select('created_at')->first();
        return view('admin', compact('rounds','round', 'ticketsCount', 'ticketsValue', 'quotas', 'isRoundOld'));
    }

    public function rollNumbers(Request $request)
    {
        $numbers = Arr::random(range(1, 10), 5);

        Round::create([
            'round' => $request->get('round'),
            'numbers' => $numbers,
            'bank' => $request->get('bank'),
        ]);

        return redirect()->route('admin.view', ['round' => $request->get('round')]);
    }

}
