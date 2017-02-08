<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Employee as EmployeeFactory;
use Payroll\PaymentClassification\Factory as ClassificationFactory;
use Payroll\PaymentSchedule\Factory as ScheduleFactory;

class ChangeSalariedPaymentClassification extends ChangePaymentClassification
{
    /**
     * @var float
     */
    private $salary;

    /**
     * ChangeHourlyPaymentClassification constructor.
     *
     * @param Employee $employee
     * @param $salary
     */
    public function __construct(Employee $employee, $salary)
    {
        parent::__construct($employee);
        $this->salary = $salary;
    }

    /**
     * @return PaymentClassification
     */
    protected function getPaymentClassification()
    {
        $paymentClassification = ClassificationFactory::createClassificationByData([
            'salary' => $this->salary
        ]);

        $paymentClassification->setEmployee($this->employee);

        return $paymentClassification;
    }

    /**
     * @return PaymentSchedule
     */
    protected function getPaymentSchedule()
    {
        return ScheduleFactory::createScheduleByData([
            'salary' => $this->salary
        ]);
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return EmployeeFactory::SALARIED;
    }
}
