<?php

namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Ecommerce\Purchaseable\Events as PurchaseableEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
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
