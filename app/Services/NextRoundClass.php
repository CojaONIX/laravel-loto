<?php

namespace App\Services;

use Carbon\Carbon;

class NextRoundClass
{

    public $round;
    public $date;
    public $formated;
    public $roundRoll;

    public function __construct()
    {
        $roundStartsAt = config('loto.round');

        //$nextDate = Carbon::create(2024, 12, 26, 12,5,0, 'Europe/Belgrade')
        $nextDate = Carbon::now('Europe/Belgrade')
                    ->subHours($roundStartsAt['hour'])
                    ->subMinutes($roundStartsAt['minute']);

        $roundStartsAt['day'] == 'Everyday' ? $nextDate->addDay() : $nextDate->next($roundStartsAt['day']);
        $nextDate->hour($roundStartsAt['hour'])->minute($roundStartsAt['minute'])->second(0);

        $this->date = $nextDate;

        $round = $roundStartsAt['day'] == 'Everyday' ? $nextDate->dayOfYear : $nextDate->weekOfYear;
        $this->round = $roundStartsAt['add'] + $round;

        $this->formated = $this->date->year . '-' . str_pad($this->round, 4, "0", STR_PAD_LEFT);
        $this->roundRoll = $this->date->year . '-' . str_pad($this->round - 1, 4, "0", STR_PAD_LEFT);

        dd($this);
    }

}
