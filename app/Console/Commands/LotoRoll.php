<?php

namespace App\Console\Commands;

use App\Models\Credit;
use App\Models\Round;
use App\Models\Ticket;
use App\Services\NextRoundClass;
use Illuminate\Console\Command;
use Lotto;

class LotoRoll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loto:roll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $round = new NextRoundClass();
        $round = $round->roundRoll;

        $configLotto = config('loto');
        $numbers = Lotto::getRandomCombination($configLotto['combination']);

        $winTickets = [];
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
    }
}
