<?php

namespace Payroll\Transaction\Change;

use Payroll\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;

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
}
