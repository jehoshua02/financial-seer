<?php

namespace FinancialSeer;

use \FinancialSeer\Projection\Income;
use \FinancialSeer\Projection\YearMonth;

class Projection
{
    protected $config = [
        "account" => [
            "balance" => 0,
        ],
        "income" => []
    ];

    public function __construct($config = null) {
        if ($config === null) { return; }

        foreach ($config as $type => $value) {
            $method = "add" . ucfirst($type);
            if ($type === "income") {
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
        $this->config["income"][] = new Projection\Income($config);
    }

    public function getMonth($yearMonth)
    {
        $yearMonth = YearMonth::from($yearMonth);
        return [
            "income" => $this->getIncome($yearMonth),
            "fixedExpense" => 0,
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

    protected function getAccountBalance(YearMonth $yearMonth)
    {
        return array_sum([
            $this->config["account"]["balance"],
            $this->incomeAccumulated($yearMonth),
        ]);
    }
}
