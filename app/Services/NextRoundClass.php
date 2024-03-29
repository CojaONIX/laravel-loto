<?php

namespace App\Services;

use Carbon\Carbon;

class NextRoundClass
{

    public int $round;
    public object $date;
    public string $formated;

    public function __construct(bool $prevRound = false)
    {
        $roundStartsAt = config('loto.round');

        //$nextDate = Carbon::create(2024, 11, 29, 12,0,0, 'Europe/Belgrade')
        $nextDate = Carbon::now('Europe/Belgrade')
                    ->subHours($roundStartsAt['hour'])
                    ->subMinutes($roundStartsAt['minute']);

        $roundStartsAt['day'] == 'Everyday' ? $nextDate->addDay() : $nextDate->next($roundStartsAt['day']);
        $nextDate->hour($roundStartsAt['hour'])->minute($roundStartsAt['minute'])->second(0);
        if($prevRound)
        {
            $nextDate->subDays($roundStartsAt['day'] == 'Everyday' ? 1 : 7);
        }
        $this->date = $nextDate;

//        $nextDateClone = clone $nextDate;
//        dd($nextDate->weekOfYear, $nextDate->diffInWeeks($nextDateClone->firstOfYear()));
        $round = $roundStartsAt['day'] == 'Everyday' ? $nextDate->dayOfYear : $nextDate->weekOfYear;
        $this->round = $roundStartsAt['add'] + $round;

        $this->formated = $this->date->year . '-' . str_pad($this->round, 4, "0", STR_PAD_LEFT);

        dd($this->date, $this->formated);
    }

}
