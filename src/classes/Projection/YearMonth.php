<?php

namespace FinancialSeer\Projection;

class YearMonth extends Model
{
    protected $year;
    protected $month;

    public static function from($yearMonth)
    {
        if ($yearMonth instanceof YearMonth) {
            return $yearMonth;
        }

        return new YearMonth([
            "year" => $yearMonth[0],
            "month" => $yearMonth[1],
        ]);
    }

    public function monthsBetween(YearMonth $that)
    {
        return abs($this->toInt() - $that->toInt()) + 1;
    }

    public function isWithin(YearMonth $start, YearMonth $end)
    {
        return $start->toInt() <= $this->toInt() && $this->toInt() <= $end->toInt();
    }

    public function min(YearMonth $that)
    {
        return ($this->lessThan($that)) ? $this : $that;
    }

    public function lessThan(YearMonth $that)
    {
        return $this->toInt() < $that->toInt();
    }

    public function toInt()
    {
        return $this->year * 12 + $this->month;
    }

    protected function setYear($year)
    {
        $this->year = $year;
    }

    protected function setMonth($month)
    {
        $this->month = $month;
    }
}
