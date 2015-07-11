<?php

namespace FinancialSeer\Projection;

abstract class Model
{
    public function __construct($config)
    {
        foreach ($config as $key => $value) {
            $method = "set" . ucfirst($key);
            $this->$method($value);
        }
    }
}
