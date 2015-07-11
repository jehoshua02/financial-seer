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
        $config["start"] = YearMonth::from($config["start"]);
        if (!empty($config["end"])) {
            $config["end"] = YearMonth::from($config["end"]);
        }
        $this->config["income"][] = new Projection\Income($config);
    }

    public function getMonth($year, $month)
    {
        return [
            "income" => $this->getIncome($year, $month),
            "fixedExpense" => 0,
            "variableExpense" => 0, // TODO
            "debtPayment" => 0, // TODO
            "mortgagePayment" => 0, // TODO
            "accountBalance" => $this->getAccountBalance($year, $month),
        ];
    }

    protected function getIncome($year, $month)
    {
        $sum = 0;
        foreach ($this->config["income"] as $income) {
            $sum += $income->getMonth(YearMonth::from([$year, $month]));
        }
        return $sum;
    }

    protected function getAccountBalance($year, $month)
    {
        return array_sum([
            $this->config["account"]["balance"],
            $this->getIncome($year, $month),
        ]);
    }
}
