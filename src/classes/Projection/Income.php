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
        $end = empty($this->end) ? $yearMonth : $yearMonth->min($this->end);
        $months = $start->monthsBetween($end);
        return $this->salary / 12 * $months;
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
