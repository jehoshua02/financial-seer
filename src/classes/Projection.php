<?php

namespace FinancialSeer;

class Projection
{
    protected $config = [
        "account" => [
            "balance" => 0,
        ],
        "income" => [],
        "fixedExpense" => [
            "start" => null,
            "amount" => 0,
        ]
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
        $this->config["income"][] = $config;
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
        $sum = 0;
        foreach ($this->config["income"] as $income) {
            $start = $income["start"];
            $end = empty($income["end"]) ? [$year, $month] : $this->minYearMonth($income["end"], [$year, $month]);
            $months = $this->getMonths($start, $end);
            $sum += $income["salary"] / 12 * $months;
        }
        return $sum;
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

    protected function minYearMonth($yearMonth1, $yearMonth2)
    {
        if ($yearMonth1[0] < $yearMonth2 && $yearMonth1[1] < $yearMonth2[1]) {
            return $yearMonth1;
        } else {
            return $yearMonth2;
        }
    }
}
