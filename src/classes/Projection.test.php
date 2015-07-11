<?php

use \FinancialSeer\Projection;

class ProjectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getMonthProvider
     */
    public function testGetMonth($data)
    {
        $projection = new Projection($data["input"]);
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
