<?php

namespace FinancialSeer\Projection;

// TODO research to calculate pmi: http://homeguides.sfgate.com/calculate-pmi-mortgage-insurance-7763.html
// TODO research to calculate insurance
// TODO research to calculate taxes
class Mortgage extends Model
{
    protected $principal;
    protected $rate;
    protected $years;
    protected $insurance = 0;
    protected $privateMortgageInsurance = 0;
    protected $tax = 0;
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
        $base = $this->principal * $rate / (1 - pow((1 + $rate), -$months));
        return array_sum([
            $base,
            $this->insurance,
            $this->privateMortgageInsurance,
            $this->tax,
        ]);
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

    protected function setInsurance($insurance)
    {
        $this->insurance = $insurance;
    }

    protected function setPrivateMortgageInsurance($privateMortgageInsurance)
    {
        $this->privateMortgageInsurance = $privateMortgageInsurance;
    }

    protected function setTax($tax)
    {
        $this->tax = $tax;
    }

    protected function setStart($start)
    {
        $this->start = YearMonth::from($start);
    }
}
