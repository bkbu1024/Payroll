<?php

namespace Payroll\Contract\Relation;

interface HasEmployee
{
    /**
     * @return int
     */
    public function getEmployeeId();

    /**
     * @param int $employeeId
     */
    public function setEmployeeId($employeeId);
}