<?php

use \FinancialSeer\Projection;

class ProjectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group wip
     * @dataProvider getMonthProvider
     */
    public function testGetMonth($dataName)
    {
        $data = $this->getData($dataName);
        $projection = new Projection();
        if (array_key_exists("account", $data["input"])) {
            call_user_func([$projection, "addAccount"], $data["input"]["account"]);
        }
        if (array_key_exists("income", $data["input"])) {
            call_user_func([$projection, "addIncome"], $data["input"]["income"]);
        }
        $actual = call_user_func_array([$projection, "getMonth"], $data["args"]);
        $this->assertEquals($data["expected"], $actual);
    }

    /**
     * dataProvider for testGetMonth
     */
    public function getMonthProvider()
    {
        $scenarios = [
            "getMonth/empty",
            "getMonth/justAnAccount",
            "getMonth/income",
        ];

        return array_map(function ($scenario) {
            return [$scenario];
        }, $scenarios);
    }

    protected function getData($dataName)
    {
        $path = preg_replace('/.php$/', '.data', __FILE__);
        $file = $path . '/' . $dataName . '.json';
        $contents = file_get_contents($file);
        return json_decode($contents, true);
    }
}
