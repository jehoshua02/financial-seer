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

        if (is_int($yearMonth)) {
            $month = $yearMonth % 12;
            $year = ($yearMonth - $month) / 12;
            $month = max($month, 1);
        }

        if (is_array($yearMonth)) {
            $year = $yearMonth[0];
            $month = $yearMonth[1];
        }

        return new YearMonth([
            "year" => $year,
            "month" => $month,
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

    public function __get($name)
    {
        if (!property_exists($this, $name)) { return; }
        return $this->$name;
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
