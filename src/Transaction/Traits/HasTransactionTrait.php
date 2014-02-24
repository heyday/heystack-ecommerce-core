<?php

namespace Heystack\Subsystem\Ecommerce\Transaction\Traits;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;

/**
 * Class HasTransactionTrait
 * @package Heystack\Subsystem\Ecommerce\Transaction\Traits
 */
trait HasTransactionTrait
{
    /**
     * @var \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface
     */
    protected $transaction;

    /**
     * @param \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     */
    public function setTransaction(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}