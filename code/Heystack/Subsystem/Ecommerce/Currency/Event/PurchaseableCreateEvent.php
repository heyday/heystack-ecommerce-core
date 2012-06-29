<?php

namespace Heystack\Subsystem\Currency\Event;

use Symfony\Component\EventDispatcher\Event;

class PurchaseableCreateEvent extends Event
{
    protected $purchaseable;

    public function __construct($purchaseable)
    {
        $this->purchaseable = $purchaseable;
    }

    public function getOrder()
    {
        return $this->purchaseable;
    }
}