<?php

use \FinancialSeer\Account;
use \FinancialSeer\Income;

class AccountTest extends PHPUnit_Framework_TestCase
{
    public function testConfigureAccountWithIncomeAndSnapshot()
    {
        $account = new Account(array(
            "openDate" => "2014-01-01",
            "balance" => 1500.00,
        ));

        $income = new Income(array(
            "salary" => 70000.00,
            "startDate" => "2014-07-01",
        ));

        $account->addIncome($income);

        $actual = $account->snapshot("2014-12-01");
        $expected = array(
            "balance" => 36500.00
        );

        $this->assertEquals($expected, $actual);
    }
}
