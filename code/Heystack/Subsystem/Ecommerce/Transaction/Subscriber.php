<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Subsystem\Ecommerce\Transaction\Event\TransactionStoredEvent;

class Subscriber implements EventSubscriberInterface
{
    protected $transaction;
    protected $eventDispatcher;
    protected $storageService;
    

    public function __construct(TransactionInterface $transaction, EventDispatcherInterface $eventDispatcher, $storageService)
    {
        $this->transaction = $transaction;
        $this->eventDispatcher = $eventDispatcher;
        $this->storageService = $storageService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::UPDATE => array('onUpdate',0),
            Events::STORE => array('onStore', 0)
        );
    }

    public function onUpdate()
    {
        $this->transaction->updateTotal();
        $this->eventDispatcher->dispatch(Events::UPDATED);
    }
    
    public function onStore() 
    {
              
        $parentID = $this->storageService->process($this->transaction);
        
        $event = new TransactionStoredEvent($parentID);
        
        $this->eventDispatcher->dispatch(Events::STORED, $event);

    }

}
