<?php

namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Ecommerce\Purchasable\Events as PurchasableEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {

        return array(
            PurchasableEvents::PURCHASABLE_CREATE     => array('onPurchasableCreate', 0),
        );
    }

    public function onPurchasableCreate(Event $event)
    {
        \HeydayLog::log($event);
    }
}
