<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Interfaces\TransactionInterface;

class Subscriber implements EventSubscriberInterface
{
    protected $transaction;
    protected $eventDispatcher;

    public function __construct(TransactionInterface $transaction, EventDispatcherInterface $eventDispatcher )
    {
        $this->transaction = $transaction;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::UPDATE_TRANSACTION => array('onUpdate',0)
        );
    }

    public function onUpdate()
    {
        $this->transaction->updateTotal();
        $this->eventDispatcher->dispatch(Events::TRANSACTION_UPDATED);
    }

}
