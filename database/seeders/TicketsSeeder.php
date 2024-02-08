<?php

namespace Database\Seeders;

use App\Models\Credit;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nextRound = Ticket::nextRound();

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

            for($i=0; $i<$ticketsCount; $i++)
            {
                $numbers = Arr::random(range(1, $combination['from']), $combination['find']);

                Credit::create([
                    'user_id' => $user_id,
                    'type' => 2,
                    'amount' => -$combination['price'],
                ]);

                Ticket::create([
                    'user_id' => $user_id,
                    'round' => '2024-' . str_pad($round, 4, "0", STR_PAD_LEFT),
                    'numbers' => $numbers
                ]);

            }
        }

        $console->info("Kreitano je $ticketsSum tiketa");

    }
}
