<?php

namespace Payroll\Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;
use SplDoublyLinkedList;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected $faker = null;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    protected function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection 	= new ReflectionClass(get_class($object));
        $method 		= $reflection->getMethod($methodName);

        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    protected function getMockObjects(array $data)
    {
        $mocks = [];
        foreach ($data as $class => $methods) {
            $mock = $this->getMockBuilder($class)
                ->setMethods(array_keys($methods))
                ->getMock();

            foreach ($methods as $method => $attributes) {
                $times = array_get($attributes, 'times', 'any');
                $mock->expects($this->{$times}())
                    ->method($method)
                    ->will($this->returnValue($attributes['return']));
            }

            $mocks[$class] = $mock;
        }

        return $mocks;
    }

    /**
     * @param $class
     * @param array $methods
     * @return mixed
     */
    protected function getMockObject($class, array $methods)
    {
        $data = [
            $class => $methods
        ];

        $objects = $this->getMockObjects($data);
        return $objects[$class];

    }
}
