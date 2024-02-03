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
        $console = $this->command->getOutput();
        $round = $console->ask('Za koje kolo zelite tikete?', 99);
        $min = $console->ask('Minimalno tiketa po igracu?', 10);
        $max = $console->ask('Maximalno tiketa po igracu?', 20);

        $users = User::select('id')->orderBy('id')->pluck('id');
        $ticketsSum = 0;
        foreach ($users as $user_id)
        {
            $ticketsCount = rand($min, $max);
            $ticketsSum += $ticketsCount;
            Credit::create([
                'user_id' => $user_id,
                'type' => 0,
                'amount' => $ticketsCount * 100
            ]);

            for($i=0; $i<$ticketsCount; $i++)
            {
                $numbers = Arr::random(range(0, 9), 5);
                $ticket = Ticket::create([
                    'year' => Carbon::now()->year,
                    'round' => $round,
                    'numbers' => $numbers,
                    'winning' => 0,
                    'paid' => false
                ]);

                Credit::create([
                    'user_id' => $user_id,
                    'type' => 2,
                    'amount' => -100,
                    'ticket_id' => $ticket->id
                ]);
            }
        }


        $console->info("Kreitano je $ticketsSum tiketa");

    }
}
