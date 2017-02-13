<?php

use Payroll\Tests\TestCase;

class ApiEmployeeTest extends TestCase
{
    public function testPostEmployee()
    {
        // POST /employee
        $data = [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'salary' => $this->faker->randomFloat(2, 1200, 3500)
        ];

        $response = $this->json('POST', '/api/employee', $data);
        $employee = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data['name'], $employee['name']);
        $this->assertEquals($data['address'], $employee['address']);
        $this->assertEquals($data['salary'], $employee['salary']);
        $this->assertEquals('HOLD', $employee['payment_method']);
        $this->assertEquals('SALARIED', $employee['payment_classification']);

        $this->assertDatabaseHas('employees', ['id' => $employee['id']]);
    }

    public function testGetEmployees()
    {
        DB::table('employees')->truncate();
        $employees = factory(Payroll\Employee::class, 5)->create();

        $response = $this->json('GET', '/api/employees');
        $apiEmployees = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(count($employees), count($apiEmployees));

        foreach ($employees as $i => $employee) {
            $this->assertEquals($employee['id'], $apiEmployees[$i]['id']);
        }
    }

    public function testGetEmployee()
    {
        DB::table('employees')->truncate();
        $employees = factory(Payroll\Employee::class, 5)->create();
        $last = $employees->last();

        $response = $this->json('GET', "/api/employee/{$last->getId()}");
        $apiEmployee = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($apiEmployee['name'], $last->getName());
    }
}