<?php

namespace FinancialSeer\Projection;

class Debt extends Model
{
    protected $principal;
    protected $rate;
    protected $years;
    protected $start;

    public function getMonth($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = YearMonth::from($start->toInt() + $this->years * 12);
        $inRange = $yearMonth->isWithin($start, $end);
        return $inRange ? $this->getMonthlyPayment() : 0;
    }

    public function accumulated($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        $start = $this->start;
        $end = YearMonth::from($start->toInt() + $this->years * 12);
        $end = $end->min($yearMonth);
        $months = $start->monthsBetween($end);
        return $this->getMonthlyPayment() * $months;
    }

    protected function getMonthlyPayment()
    {
        // A = P (r / 12) / (1 - ( 1 + r / 12) -m )
        $rate = $this->rate / 12;
        $months = $this->years * 12;
        return $this->principal * $rate / (1 - pow((1 + $rate), -$months));
    }

    protected function setPrincipal($principal)
    {
        $this->principal = $principal;
    }

    protected function setRate($rate)
    {
        $this->rate = $rate;
    }

    protected function setYears($years)
    {
        $this->years = $years;
    }

    protected function setStart($start)
    {
        $this->start = YearMonth::from($start);
    }
}
