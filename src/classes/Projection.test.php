<?php

use \FinancialSeer\Projection;

class ProjectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getMonthProvider
     */
    public function testGetMonth($data)
    {
        $projection = new Projection();
        if (array_key_exists("account", $data["input"])) {
            call_user_func([$projection, "addAccount"], $data["input"]["account"]);
        }
        if (array_key_exists("income", $data["input"])) {
            call_user_func([$projection, "addIncome"], $data["input"]["income"]);
        }
        if (array_key_exists("fixedExpense", $data["input"])) {
            call_user_func([$projection, "addFixedExpense"], $data["input"]["fixedExpense"]);
        }
        $actual = call_user_func_array([$projection, "getMonth"], $data["args"]);
        $this->assertEquals($data["expected"], $actual);
    }

    /**
     * dataProvider for testGetMonth
     */
    public function getMonthProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, 'getMonth');
    }
}
