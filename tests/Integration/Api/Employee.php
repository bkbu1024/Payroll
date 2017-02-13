<?php

namespace Payroll\Tests\Integration\Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Payroll\Employee;
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
        // GET employees
        DB::table('employees')->truncate();
        $employees = factory(Employee::class, 5)->create();

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
        // GET employee/{employee}
        DB::table('employees')->truncate();
        $employees = factory(Employee::class, 5)->create();
        $last = $employees->last();

        $response = $this->json('GET', "/api/employee/{$last->getId()}");
        $apiEmployee = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($apiEmployee['name'], $last->getName());
    }

    public function testDeleteEmployee()
    {
        // DELETE employee/{employee}
        DB::table('employees')->truncate();
        $employees = factory(Employee::class, 5)->create();
        $last = $employees->last();

        $response = $this->json('DELETE', "/api/employee/{$last->getId()}");
        $apiEmployee = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($apiEmployee['name'], $last->getName());

        try {
            Employee::findOrFail($last->getId());
            $this->fail();
        } catch (ModelNotFoundException $ex) {
            $this->assertTrue(true);
        }
    }
}