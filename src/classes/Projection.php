<?php

namespace FinancialSeer;

class Projection
{
    protected $config = [
        "account" => [
            "balance" => 0,
        ],
        "income" => [
            "start" => null,
            "salary" => null,
        ],
        "fixedExpense" => [
            "start" => null,
            "amount" => 0,
        ]
    ];

    public function __construct($config = null) {
        if ($config === null) { return; }

        foreach ($config as $key => $value) {
            $method = "add" . ucfirst($key);
            $this->$method($value);
        }
    }

    public function addAccount($config)
    {
        $this->config["account"] = $config;
    }

    public function addIncome($config)
    {
        $this->config["income"] = $config;
    }

    public function addFixedExpense($config)
    {
        $this->config["fixedExpense"] = $config;
    }

    public function getMonth($year, $month)
    {
        return [
            "income" => $this->getIncome($year, $month),
            "fixedExpense" => $this->getFixedExpense($year, $month),
            "variableExpense" => 0,
            "debtPayment" => 0,
            "mortgagePayment" => 0,
            "accountBalance" => $this->getAccountBalance($year, $month),
        ];
    }

    protected function getIncome($year, $month)
    {
        $config = $this->config["income"];
        if ($config["start"] === null) {
            return 0;
        }
        $months = $this->getMonths($config["start"], [$year, $month]);
        return $config["salary"] / 12 * $months;
    }

    protected function getAccountBalance($year, $month)
    {
        return array_sum([
            $this->config["account"]["balance"],
            $this->getIncome($year, $month),
            -$this->getFixedExpense($year, $month),
        ]);
    }

    protected function getFixedExpense($year, $month)
    {
        $config = $this->config["fixedExpense"];
        if ($config["start"] === null) {
            return 0;
        }
        $months = $this->getMonths($config["start"], [$year, $month]);
        return $config["amount"] * $months;
    }

    protected function getMonths($startYearMonth, $endYearMonth)
    {
        $startDate = join("-", [
            $startYearMonth[0],
            $startYearMonth[1],
            1,
        ]);

        $endDate = join("-", [
            $endYearMonth[0],
            $endYearMonth[1],
            1,
        ]);

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);
        $diff = $startDate->diff($endDate);
        $months = $diff->y * 12 + $diff->m + 1;
        return $months;
    }
}
