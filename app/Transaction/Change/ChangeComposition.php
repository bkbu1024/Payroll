<?php

namespace Payroll\Transaction\Change;

class ChangeComposition extends ChangeEmployee
{
    protected $transactions = [];

    /**
     * @param ChangeEmployee $transaction
     */
    public function addTransaction(ChangeEmployee $transaction)
    {
        $this->transactions[] = $transaction;
    }

    protected function change()
    {
        foreach ($this->transactions as $transaction) {
            /**
             * @var ChangeEmployee $transaction
             */

            $transaction->execute();
        }
    }
}