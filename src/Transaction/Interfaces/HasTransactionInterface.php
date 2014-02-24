<?php

namespace Heystack\Ecommerce\Transaction\Interfaces;

/**
 * Interface HasnTransactionInterface
 * @package Heystack\Ecommerce\Transaction\Interfaces
 */
interface HasTransactionInterface
{
    /**
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     */
    public function setTransaction(TransactionInterface $transaction);
    
    /**
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface
     */
    public function getTransaction();
} 