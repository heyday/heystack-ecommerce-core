<?php

namespace Heystack\Ecommerce\Transaction\Traits;

use Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface;

/**
 * Class HasTransactionTrait
 * @package Heystack\Ecommerce\Transaction\Traits
 */
trait HasTransactionTrait
{
    /**
     * @var \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface
     */
    protected $transaction;

    /**
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     * @return void
     */
    public function setTransaction(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}