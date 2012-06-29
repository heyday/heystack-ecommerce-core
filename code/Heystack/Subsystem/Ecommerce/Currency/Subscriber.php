<?php

namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Purchaseable\Events as PurchaseableEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class Subscriber implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        
        return array(
            PurchaseableEvents::PURCHASEABLE_CREATE     => array('onPurchaseableCreate', 0),
        );
    }

    public function onPurchaseableCreate(Event $event)
    {
        \HeydayLog::log($event);
    }
}