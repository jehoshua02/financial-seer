<?php

namespace FinancialSeer;

use \FinancialSeer\Projection\YearMonth;
use \FinancialSeer\Projection\Income;
use \FinancialSeer\Projection\FixedExpense;

class Projection
{
    protected $config = [
        "account" => [
            "balance" => 0,
        ],
        "income" => [],
        "fixedExpense" => [],
    ];

    public function __construct($config = null) {
        if ($config === null) { return; }

        $hasMany = [
            "income",
            "fixedExpense",
        ];

        foreach ($config as $type => $value) {
            $method = "add" . ucfirst($type);
            if (in_array($type, $hasMany)) {
                foreach ($value as $item) {
                    $this->$method($item);
                }
            } else {
                $this->$method($value);
            }
        }
    }

    public function addAccount($config)
    {
        $this->config["account"] = $config;
    }

    public function addIncome($config)
    {
        $this->config["income"][] = new Income($config);
    }

    public function addFixedExpense($config)
    {
        $this->config["fixedExpense"][] = new FixedExpense($config);
    }

    public function getMonth($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        return [
            "income" => $this->getIncome($yearMonth),
            "fixedExpense" => $this->getFixedExpense($yearMonth),
            "variableExpense" => 0, // TODO
            "debtPayment" => 0, // TODO
            "mortgagePayment" => 0, // TODO
            "accountBalance" => $this->getAccountBalance($yearMonth),
        ];
    }

    protected function getIncome(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["income"] as $income) {
            $sum += $income->getMonth($yearMonth);
        }
        return $sum;
    }

    protected function incomeAccumulated(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["income"] as $income) {
            $sum += $income->accumulated($yearMonth);
        }
        return $sum;
    }

    protected function getFixedExpense(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["fixedExpense"] as $fixedExpense) {
            $sum += $fixedExpense->getMonth($yearMonth);
        }
        return $sum;
    }

    protected function fixedExpenseAccumulated(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["fixedExpense"] as $fixedExpense) {
            $sum += $fixedExpense->accumulated($yearMonth);
        }
        return $sum;
    }

    protected function getAccountBalance(YearMonth $yearMonth)
    {
        return array_sum([
            $this->config["account"]["balance"],
            $this->incomeAccumulated($yearMonth),
            -$this->fixedExpenseAccumulated($yearMonth),
        ]);
    }
}
