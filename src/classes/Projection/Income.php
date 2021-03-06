<?php

namespace FinancialSeer\Projection;

class Income extends Model
{
    protected $salary;
    protected $start;
    protected $end;

    public function getMonth($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = !empty($this->end) ? $this->end : $yearMonth;
        $inRange = $yearMonth->isWithin($start, $end);
        return $inRange ? $this->salary / 12 : 0;
    }

    public function accumulated($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = !empty($this->end) ? $this->end : $yearMonth;
        $end = $end->min($yearMonth);
        $months = $start->monthsBetween($end);
        return $this->salary / 12 * $months;
    }

    protected function setSalary($salary)
    {
        $this->salary = $salary;
    }

    protected function setStart($start)
    {
        $this->start = YearMonth::from($start);
    }

    protected function setEnd($end)
    {
        $this->end = YearMonth::from($end);
    }
}
