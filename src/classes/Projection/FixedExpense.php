<?php

namespace FinancialSeer\Projection;

class FixedExpense extends Model
{
    protected $amount;
    protected $start;
    protected $end;

    public function getMonth($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = !empty($this->end) ? $this->end : $yearMonth;
        $inRange = $yearMonth->isWithin($start, $end);
        return $inRange ? $this->amount : 0;
    }

    public function accumulated($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = !empty($this->end) ? $this->end : $yearMonth;
        $end = $end->min($yearMonth);
        $months = $start->monthsBetween($end);
        return $this->amount * $months;
    }

    protected function setAmount($amount)
    {
        $this->amount = $amount;
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
