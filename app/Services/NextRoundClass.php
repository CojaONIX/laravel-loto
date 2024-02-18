<?php

namespace App\Services;

use Carbon\Carbon;

class NextRoundClass
{

    public $round;
    public $date;
    public $formated;

    public function __construct()
    {
        $roundStartsAt = config('loto.round');

        $firstYearRoundDate = new Carbon( "first {$roundStartsAt['day']} of January");
        $firstYearRoundDate->addHours($roundStartsAt['hour'])->subHour()->addMinutes($roundStartsAt['minute']);
        $this->round = $firstYearRoundDate->diffInWeeks(Carbon::now()) + 1 + 1;
        $this->date = $firstYearRoundDate->addWeeks($this->round - 1)->addHour();
        $this->formated = $this->date->year . '-' . str_pad($this->round, 4, "0", STR_PAD_LEFT);
    }

}
