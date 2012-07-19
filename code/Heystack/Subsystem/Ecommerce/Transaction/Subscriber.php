<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;

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
            Events::UPDATE => array('onUpdate',0),
            Events::STORE => array('onStore', 0)
        );
    }

    public function onUpdate()
    {
        $this->transaction->updateTotal();
        $this->eventDispatcher->dispatch(Events::UPDATED);
    }
    
    public function onStore() {
        
        $storage = \Heystack\Subsystem\Core\ServiceStore::getService('storage_processor_handler');
        
        $parentID = $storage->process($this->transaction);

        
        foreach($this->transaction->getModifiers() as $modifier) {

            $parentID = $storage->process($modifier, false, $parentID);
            
            if ($modifier->getIdentifier() == 'productholder') {
                
                foreach ($modifier->getPurchasables() as $purchaseable) {
                    
                    $storage->process($purchaseable, false, $parentID);
                    
                }
                
            } else if ($modifier->getIdentifier() == 'voucher_holder') {
                
                
                foreach ($modifier->getVouchers() as $voucher) {
                    
                    $storage->process($voucher, false, $parentID);
                    
                }
                
            }

        }
        
    }

}
