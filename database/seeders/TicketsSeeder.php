<?php

namespace Database\Seeders;

use App\Services\NextRoundClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Credit;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Lotto;

class TicketsSeeder extends Seeder
{
    public function run(): void
    {
        $time = Carbon::now();
        $nextRound = new NextRoundClass();

        $console = $this->command->getOutput();
        $round = $console->ask('Za koje kolo zelite tikete?', $nextRound->round);
        $min = $console->ask('Minimalno tiketa po igracu?', 10);
        $max = $console->ask('Maximalno tiketa po igracu?', 20);

        $combination = config('loto.combination');
        $users = User::where(['role' => 'fake'])->select('id')->orderBy('id')->pluck('id');
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

            $credits = [];
            $tickets = [];
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
