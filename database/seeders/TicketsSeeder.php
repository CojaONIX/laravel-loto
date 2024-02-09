<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Credit;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Lotto;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $time = Carbon::now();
        $nextRound = Lotto::nextRound();

        $console = $this->command->getOutput();
        $round = $console->ask('Za koje kolo zelite tikete?', $nextRound['round']);
        $min = $console->ask('Minimalno tiketa po igracu?', 10);
        $max = $console->ask('Maximalno tiketa po igracu?', 20);

        $combination = config('loto.combination');
        $users = User::select('id')->orderBy('id')->pluck('id');
        $ticketsSum = 0;
        foreach ($users as $user_id)
        {
            $ticketsCount = rand($min, $max);
            $ticketsSum += $ticketsCount;
            Credit::create([
                'user_id' => $user_id,
                'type' => 0,
                'amount' => $ticketsCount * $combination['price']
            ]);

            // 5 * 1000 tiketa -> 104 sec
            // 5 * 100 tiketa -> 12 sec
//            for($i=0; $i<$ticketsCount; $i++)
//            {
//                $numbers = Arr::random(range(1, $combination['from']), $combination['find']);
//
//                Credit::create([
//                    'user_id' => $user_id,
//                    'type' => 2,
//                    'amount' => -$combination['price'],
//                ]);
//
//                Ticket::create([
//                    'user_id' => $user_id,
//                    'round' => '2024-' . str_pad($round, 4, "0", STR_PAD_LEFT),
//                    'numbers' => $numbers
//                ]);
//
//            }

            $credits = array();
            $tickets = array();
            for($i=0; $i<$ticketsCount; $i++)
            {
                $numbers = Arr::random(range(1, $combination['from']), $combination['find']);

                $credits[] = [
                    'user_id' => $user_id,
                    'type' => 2,
                    'amount' => -$combination['price'],
                    'created_at' => $time,
                    'updated_at' => $time
                ];

                $tickets[] = [
                    'user_id' => $user_id,
                    'round' => '2024-' . str_pad($round, 4, "0", STR_PAD_LEFT),
                    'numbers' => json_encode($numbers),
                    'created_at' => $time,
                    'updated_at' => $time
                ];

            }

            Credit::insert($credits);
            Ticket::insert($tickets);
        }

        $console->info("Kreitano je $ticketsSum tiketa");

    }
}
