<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\PaymentClassification\Factory as ClassificationFactory;
use Payroll\Factory\PaymentSchedule\Factory as ScheduleFactory;

class ChangeCommissionedPaymentClassification extends ChangePaymentClassification
{
    /**
     * @var float
     */
    private $salary;
    /**
     * @var float
     */
    private $commissionRate;

    /**
     * ChangeHourlyPaymentClassification constructor.
     *
     * @param Employee $employee
     * @param $salary
     * @param $commissionRate
     */
    public function __construct(Employee $employee, $salary, $commissionRate)
    {
        parent::__construct($employee);
        $this->salary = $salary;
        $this->commissionRate = $commissionRate;
    }

    /**
     * @return PaymentClassification
     */
    protected function getPaymentClassification()
    {
        $paymentClassification = ClassificationFactory::createClassificationByData([
            'salary' => $this->salary,
            'commissionRate' => $this->commissionRate
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
            'salary' => $this->salary,
            'commissionRate' => $this->commissionRate
        ]);
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return EmployeeFactory::COMMISSION;
    }
}
