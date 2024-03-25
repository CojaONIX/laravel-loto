<?php

namespace App\Console\Commands;

use App\Models\Credit;
use Illuminate\Console\Command;

class LotoCronTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loto:cron-test';

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
        Credit::create([
           'user_id' => 1,
           'type' => 0,
           'amount' => 10
        ]);
        $this->info('bla bla');
    }
}
