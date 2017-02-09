<?php

namespace Unit\Factory\Transaction\Change;

use Payroll\Employee;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\Transaction\Change\PaymentMethod;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Change\ChangeDirectMethod;
use Payroll\Transaction\Change\ChangeHoldMethod;
use Payroll\Transaction\Change\ChangeMailMethod;

class PaymentMethodTest extends TestCase
{
    protected $employee = null;

    public function __construct()
    {
        parent::__construct();
        $this->employee = new Employee;
    }

    public function testCreateDirect()
    {
        $data = [
            'bank' => $this->faker->company,
            'account' => $this->faker->bankAccountNumber
        ];

        $transaction = PaymentMethod::create($this->employee, $data);
        $this->assertTrue($transaction instanceof ChangeDirectMethod);
    }

    public function testCreateMail()
    {
        $data = [
            'address' => $this->faker->address
        ];

        $transaction = PaymentMethod::create($this->employee, $data);
        $this->assertTrue($transaction instanceof ChangeMailMethod);
    }

    public function testCreateDefault()
    {
        $transaction = PaymentMethod::create($this->employee);
        $this->assertTrue($transaction instanceof ChangeHoldMethod);
    }
}