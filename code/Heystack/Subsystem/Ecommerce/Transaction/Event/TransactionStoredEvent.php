<?php
namespace Heystack\Subsystem\Ecommerce\Transaction\Event;

use Symfony\Component\EventDispatcher\Event;
use Heystack\Subsystem\Ecommerce\Transaction\Transaction;

class TransactionStoredEvent extends Event
{
    protected $transactionID;

    public function __construct($transactionID)
    {
        $this->transactionID = $transactionID;
    }

    public function getTransactionID()
    {
        return $this->transactionID;
    }
}