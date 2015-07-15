<?php

namespace FinancialSeer;

use \FinancialSeer\Projection\YearMonth;
use \FinancialSeer\Projection\Income;
use \FinancialSeer\Projection\FixedExpense;
use \FinancialSeer\Projection\VariableExpense;
use \FinancialSeer\Projection\Debt;
use \FinancialSeer\Projection\Mortgage;

class Projection
{
    protected $config = [
        "account" => [
            "balance" => 0,
        ],
        "income" => [],
        "fixedExpense" => [],
        "variableExpense" => [],
        "debt" => [],
        "mortgage" => [],
    ];

    public function __construct($config = null) {
        if ($config === null) { return; }

        $hasMany = [
            "income",
            "fixedExpense",
            "variableExpense",
            "debt",
            "mortgage",
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

    public function getMonth($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        return [
            "income" => $this->getIncome($yearMonth),
            "fixedExpense" => $this->getFixedExpense($yearMonth),
            "variableExpense" => $this->getVariableExpense($yearMonth),
            "debt" => $this->getDebt($yearMonth),
            "mortgage" => $this->getMortgage($yearMonth),
            "accountBalance" => $this->getAccountBalance($yearMonth),
        ];
    }

    protected function addIncome($config)
    {
        $this->config["income"][] = new Income($config);
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

    protected function addFixedExpense($config)
    {
        $this->config["fixedExpense"][] = new FixedExpense($config);
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

    protected function addVariableExpense($config)
    {
        $this->config["variableExpense"][] = new VariableExpense($config);
    }

    protected function getVariableExpense(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["variableExpense"] as $variableExpense) {
            $sum += $variableExpense->getMonth($yearMonth);
        }
        return $sum;
    }

    protected function variableExpenseAccumulated(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["variableExpense"] as $variableExpense) {
            $sum += $variableExpense->accumulated($yearMonth);
        }
        return $sum;
    }

    protected function addDebt($config)
    {
        $this->config["debt"][] = new Debt($config);
    }

    protected function getDebt(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["debt"] as $debt) {
            $sum += $debt->getMonth($yearMonth);
        }
        return $sum;
    }

    protected function debtAccumulated(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["debt"] as $debt) {
            $sum += $debt->accumulated($yearMonth);
        }
        return $sum;
    }

    protected function addMortgage($config)
    {
        $this->config["mortgage"][] = new Mortgage($config);
    }

    protected function getMortgage(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["mortgage"] as $mortgage) {
            $sum += $mortgage->getMonth($yearMonth);
        }
        return $sum;
    }

    protected function mortgageAccumulated(YearMonth $yearMonth)
    {
        $sum = 0;
        foreach ($this->config["mortgage"] as $mortgage) {
            $sum += $mortgage->accumulated($yearMonth);
        }
        return $sum;
    }

    protected function addAccount($config)
    {
        $this->config["account"] = $config;
    }

    protected function getAccountBalance(YearMonth $yearMonth)
    {
        return array_sum([
            $this->config["account"]["balance"],
            $this->incomeAccumulated($yearMonth),
            -$this->fixedExpenseAccumulated($yearMonth),
            -$this->variableExpenseAccumulated($yearMonth),
            -$this->debtAccumulated($yearMonth),
            -$this->mortgageAccumulated($yearMonth),
        ]);
    }
}
