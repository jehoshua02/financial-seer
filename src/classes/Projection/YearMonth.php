<?php

namespace FinancialSeer\Projection;

class YearMonth extends Model
{
    protected $year;
    protected $month;

    public static function from($yearMonth)
    {
        return new YearMonth([
            "year" => $yearMonth[0],
            "month" => $yearMonth[1],
        ]);
    }

    public function year()
    {
        return $this->year;
    }

    public function month()
    {
        return $this->month;
    }

    public function monthsBetween(YearMonth $that)
    {
        $thisMonths = $this->year * 12 + $this->month;
        $thatMonths = $that->year() * 12 + $that->month();
        return abs($thisMonths - $thatMonths) + 1;
    }

    public function min(YearMonth $that)
    {
        return ($this->lessThan($that)) ? $this : $that;
    }

    public function lessThan(YearMonth $that)
    {
        return $this->year < $that->year() && $this->month < $that->month;
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
