<?php

namespace Heystack\Subsystem\Ecommerce\Currency\Event;

use Symfony\Component\EventDispatcher\Event;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;

class CurrencyEvent extends Event
{
    protected $currency;

    public function __construct(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {       
        return $this->currency;
    }
}