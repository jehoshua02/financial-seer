<?php

namespace FinancialSeer\Projection;

class Income extends Model
{
    protected $salary;
    protected $start;
    protected $end;

    public function getMonth(YearMonth $yearMonth)
    {
        $start = $this->start;
        $end = !empty($this->end) ? $this->end : $yearMonth;
        $inRange = $yearMonth->isWithin($start, $end);
        return $inRange ? $this->salary / 12 : 0;
    }

    public function accumulated($year, $month)
    {
        $yearMonth = YearMonth::from([$year, $month]);
        $start = $this->start;
        $end = $this->end($yearMonth)->min($yearMonth);
        $months = $start->monthsBetween($end);
        return $this->salary / 12 * $months;
    }

    protected function end($default)
    {
        return empty($this->end) ? $default : $this->end;
    }

    protected function setSalary($salary)
    {
        $this->salary = $salary;
    }

    protected function setStart(YearMonth $start)
    {
        $this->start = $start;
    }

    protected function setEnd(YearMonth $end)
    {
        $this->end = $end;
    }
}
