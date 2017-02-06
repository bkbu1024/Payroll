<?php

namespace Payroll\Contract;

interface SalesReceipt
{
    public function getId();
    public function getEmployeeId();
    public function getDate();
    public function getAmount();

    public function setId($id);
    public function setEmployeeId($employeeId);

    /**
     * @param string $date
     */
    public function setDate($date);

    public function setAmount($amount);
}