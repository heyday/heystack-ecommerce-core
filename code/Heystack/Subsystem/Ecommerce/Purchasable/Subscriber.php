<?php

namespace Heystack\Subsystem\Ecommerce\Purchasable;

use Heystack\Subsystem\Ecommerce\Currency\Events as CurrencyEvents;
use Heystack\Subsystem\Ecommerce\Currency\Event\CurrencyEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
           CurrencyEvents::CURRENCY_CHANGE => array('onCurrencyChange', 10)
        );
    }

    public function onCurrencyChange(CurrencyEvent $event)
    {
//        \HeydayLog::log('Currency did change');
        
        error_log('Currency Changed! Value:' . $event->getCurrency()->retrieveValue());
        error_log('Currency Changed! Symbol:' . $event->getCurrency()->retrieveSymbol());
    }

}
