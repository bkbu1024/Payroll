<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Transaction\Add\AddEmployee;

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
     * @return CommissionedClassification
     */
    protected function getPaymentClassification()
    {
        $paymentClassification = new CommissionedClassification($this->salary, $this->commissionRate);
        $paymentClassification->setEmployee($this->employee);

        return $paymentClassification;
    }

    /**
     * @return BiweeklySchedule
     */
    protected function getPaymentSchedule()
    {
        return new BiweeklySchedule;
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return AddEmployee::COMMISSION;
    }
}
