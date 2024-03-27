<?php

namespace App\Services;

use Carbon\Carbon;

class NextRoundClass
{

    public $round;
    public $date;
    public $timeSubHour;
    public $formated;

    public function __construct()
    {
        $roundStartsAt = config('loto.round');

        if($roundStartsAt['day'] == 'Everyday')
        {
            $firstYearRoundDate = Carbon::now()
                                            ->firstOfYear()
                                            ->hours($roundStartsAt['hour'])
                                            ->subHour()
                                            ->minutes($roundStartsAt['minute']);
            $round = $firstYearRoundDate->diffInDays(Carbon::now()->subSeconds(2) ) + 1 + 1;
            $this->date = $firstYearRoundDate->addDays($round - 1)->addHour();
        }
        else
        {
            $firstYearRoundDate = new Carbon( "first {$roundStartsAt['day']} of January");
            $firstYearRoundDate->hours($roundStartsAt['hour'])->subHour()->minutes($roundStartsAt['minute']);
            $round = $firstYearRoundDate->diffInWeeks(Carbon::now()->subSeconds(2) ) + 1 + 1;
            $this->date = $firstYearRoundDate->addWeeks($round - 1)->addHour();
        }

        $this->timeSubHour = $firstYearRoundDate->subHour()->format('H:i');
        $this->round = $roundStartsAt['add'] + $round;
        $this->formated = $this->date->year . '-' . str_pad($this->round, 4, "0", STR_PAD_LEFT);
    }

}
