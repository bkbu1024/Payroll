<?php

namespace Payroll\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection 	= new ReflectionClass(get_class($object));
        $method 		= $reflection->getMethod($methodName);

        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
