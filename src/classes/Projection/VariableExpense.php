<?php

namespace FinancialSeer\Projection;

class VariableExpense extends Model
{
    protected $amounts;
    protected $start;
    protected $end;

    public function getMonth($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = !empty($this->end) ? $this->end : $yearMonth;
        $index = $yearMonth->month - 1;
        $inRange = $yearMonth->isWithin($start, $end);
        return $inRange ? $this->amounts[$index] : 0;
    }

    public function accumulated($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = !empty($this->end) ? $this->end : $yearMonth;
        $end = $end->min($yearMonth);
        $sum = 0;
        $yearMonth = $start;
        $months = $start->monthsBetween($end);
        for ($i = 0; $i < $months; $i++) {
            $sum += $this->getMonth($yearMonth);
            $yearMonth = YearMonth::from($yearMonth->toInt() + 1);
        }
        return $sum;
    }

    protected function setAmounts($amounts)
    {
        $this->amounts = $amounts;
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
