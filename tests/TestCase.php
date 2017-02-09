<?php

namespace Payroll\Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected $faker = null;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection 	= new ReflectionClass(get_class($object));
        $method 		= $reflection->getMethod($methodName);

        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
