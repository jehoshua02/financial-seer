<?php

namespace FinancialSeer;

class Account
{
    protected $config;
    protected $incomes = array();

    /**
     * Construct method
     * @param array $config
     * @param string $config["openDate"]
     * @param float $config["balance"]
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Add income to account configuration
     * @param Income $income
     */
    public function addIncome($income)
    {
        array_push($this->incomes, $income);
    }

    /**
     * Return account snapshot for end of month
     * @param  string $date
     * @return array
     */
    public function snapshot($date)
    {
        return array(
            "balance" => $this->getBalance($date)
        );
    }

    /**
     * Gets the balance at the end of the month
     * @param  string $date
     * @return float
     */
    protected function getBalance($date)
    {
        return $this->getIncome($date) + $this->config["balance"];
    }

    /**
     * Get total accumulated income until end of month
     * @param  string $date
     * @return float
     */
    protected function getIncome($date)
    {
        $sum = 0;
        foreach ($this->incomes as $income) {
            $sum += $income->accumulateUntil($date);
        }
        return $sum;
    }
}
