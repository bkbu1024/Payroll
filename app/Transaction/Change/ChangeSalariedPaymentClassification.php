<?php

namespace Payroll\Transaction\Change;

use Payroll\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;

class ChangeSalariedPaymentClassification extends ChangePaymentClassification
{
    /**
     * @var
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
     * @return SalariedClassification
     */
    protected function getPaymentClassification()
    {
        $paymentClassification = new SalariedClassification($this->salary);
        $paymentClassification->setEmployee($this->employee);

        return $paymentClassification;
    }

    /**
     * @return MonthlySchedule
     */
    protected function getPaymentSchedule()
    {
        return new MonthlySchedule;
    }
}
