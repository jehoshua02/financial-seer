<?php

use \FinancialSeer\Income;

class IncomeTest extends PHPUnit_Framework_TestCase
{
    public function testAccumulateUntil()
    {
        $income = new Income(array(
            "startDate" => "2014-01-01",
            "salary" => 70000.00,
        ));

        $actual = $income->accumulateUntil("2014-12-01");
        $expected = 70000.00;

        $this->assertEquals($expected, $actual);
    }
}
