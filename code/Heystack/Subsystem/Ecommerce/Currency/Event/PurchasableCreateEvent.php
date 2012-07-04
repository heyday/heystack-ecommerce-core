<?php

namespace Heystack\Subsystem\Ecommerce\Currency\Event;

use Symfony\Component\EventDispatcher\Event;

class PurchasableCreateEvent extends Event
{
    protected $purchasable;

    public function __construct($purchasable)
    {
        $this->purchasable = $purchasable;
    }

    public function getOrder()
    {
        return $this->purchasable;
    }
}
