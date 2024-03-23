<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Lotto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

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
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round'); //buttons

        $round = Round::where(['round' => $round])->first();
        $report = $round ? $round->report : Lotto::getRoundReport($roundActive);
        $numbers = $round ? $round->numbers : [];

        return view('adminRound', compact('report', 'numbers', 'rounds', 'roundActive'));
    }

    public function rollNumbers(Request $request)
    {
        $round = $request->get('round');
        $configLotto = config('loto');
        $numbers = Lotto::getRandomCombination($configLotto['combination']);

        $winTickets = array();
        $minWin = min(array_keys($configLotto['wins']['percentages']));

        $tickets = Ticket::where(['round' => $round])->select(['user_id', 'numbers'])->lazy();
        foreach ($tickets as $ticket) {
            $win = count(array_intersect($numbers, $ticket->numbers));
            if ($win >= $minWin) {
                $winTickets[] = [
                    'user_id' => $ticket->user_id,
                    'type' => Credit::TYPE_WIN,
                    'amount' => $win
                ];
            }
        }

//        Ticket::where(['round' => $round])->select(['user_id', 'numbers'])->chunk(5000, function (Collection $ticketsChunk) use(&$winTickets, $minWin, $numbers) {
//            foreach ($ticketsChunk as $ticket) {
//                $win = count(array_intersect($numbers, $ticket->numbers));
//                if($win >= $minWin)
//                {
//                    $winTickets[] = [
//                        'user_id' => $ticket->user_id,
//                        'type' => Credit::TYPE_WIN,
//                        'amount' => $win
//                    ];
//                }
//            }
//        });

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
        foreach(array_chunk($winTickets,1000) as $chunk)
        {
            foreach($chunk as &$winTicket) {
                $winTicket['amount'] = $paids[$winTicket['amount']];
                $winTicket['created_at'] = $time;
                $winTicket['updated_at'] = $time;
            }

            Credit::insert($chunk);
        }

        return redirect()->route('admin.round.view', ['round' => $round]);
    }

}
