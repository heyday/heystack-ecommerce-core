<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Heystack\Subsystem\Core\ServiceStore;

class Subscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        $transaction = ServiceStore::getService(Transaction::STATE_KEY);
        $subscribedEvents = array();
        
        $updateCall = array('onUpdate',0);
        $updateEventStrings = $transaction->getUpdateEventStrings();
        
        foreach($updateEventStrings as $updateEventString){
            $subscribedEvents[$updateEventString] = $updateCall;
        }
        
        return $subscribedEvents;
    }
    
    public function onUpdate()
    {
        $transaction = ServiceStore::getService(Transaction::STATE_KEY);
        $transaction->updateTotal();
        $transaction->saveState();
    }
    
}