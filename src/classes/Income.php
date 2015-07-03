<?php

namespace FinancialSeer;

use \Moment\Moment;

class Income
{
    protected $config;

    /**
     * Construct method
     * @param array $config
     * @param string $config["startDate"]
     * @param float $config["salary"]
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Accumulate income until end of month
     * @param  string $date
     * @return float
     */
    public function accumulateUntil($date)
    {
        // prepare start date
        $start = new Moment($this->config["startDate"]);
        $start->startOf("month");

        // prepare end date
        $end = new Moment($date);
        $end->endOf("month");

        // find months
        $months = floor(abs($end->from($start)->getMonths()));

        // calculate income
        return ($this->config["salary"] / 12) * $months;
    }
}
