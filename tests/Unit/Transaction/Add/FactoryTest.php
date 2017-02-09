<?php

namespace Unit\Transaction\Add;

use Exception;
use Faker\Factory;
use Faker\Generator;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Add\AddSalariedEmployee;
use Payroll\Transaction\Add\Factory as AddTransactionFactory;

class FactoryTest extends TestCase
{
    /**
     * @var Generator
     */
    protected $faker = null;

    /**
     * @var array
     */
    protected $baseData = [];

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->baseData = [
            'name' => $this->faker->name,
            'address' => $this->faker->address
        ];
    }

    public function testCreateCommissioned()
    {
        $this->baseData['salary'] = $this->faker->randomFloat(2, 1000, 2500);
        $this->baseData['commissionRate'] = $this->faker->randomFloat(2, 10, 30);

        $transaction = AddTransactionFactory::create($this->baseData);
        $this->assertTrue($transaction instanceof AddCommissionedEmployee);
    }

    public function testCreateSalaried()
    {
        $this->baseData['salary'] = $this->faker->randomFloat(2, 1000, 2500);

        $transaction = AddTransactionFactory::create($this->baseData);
        $this->assertTrue($transaction instanceof AddSalariedEmployee);
    }

    public function testCreateHourly()
    {
        $this->baseData['hourlyRate'] = $this->faker->randomFloat(2, 10, 30);

        $transaction = AddTransactionFactory::create($this->baseData);
        $this->assertTrue($transaction instanceof AddHourlyEmployee);
    }

    public function testCreateInvalidData()
    {
        $this->baseData['invalid'] = $this->faker->randomFloat(2, 10, 30);

        try {
            AddTransactionFactory::create($this->baseData);
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Never should reach here', $ex->getMessage());
        }
    }
}