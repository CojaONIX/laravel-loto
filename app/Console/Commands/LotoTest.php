<?php

namespace App\Console\Commands;

use App\Models\Credit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LotoTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loto:test';

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
        $date = Carbon::create(2024, 12, 26, 13,0,0, 'Europe/Belgrade');

        for($i=0; $i<15; $i++)
        {
            $date->addDay();
            $this->info($date . " --- " . $date->weekOfYear . " --- " . (int) (($date->dayOfYear - 1) / 7) + 1);
        }


    }
}
