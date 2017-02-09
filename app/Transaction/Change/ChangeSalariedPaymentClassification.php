<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\PaymentClassification\Factory as ClassificationFactory;
use Payroll\Factory\PaymentSchedule\Factory as ScheduleFactory;

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
     * @param $hourlyRate
     */
    public function __construct(Employee $employee, $hourlyRate)
    {
        parent::__construct($employee);
        $this->salary = $hourlyRate;
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
