<?php

namespace Heystack\Subsystem\Ecommerce\Purchasable\Interfaces;

use Heystack\Subsystem\Core\State\State;
use Symfony\Component\EventDispatcher\EventDispatcher;

interface PurchasableInterface
{

    public function getIdentifier();
    public function getPrice();
    public function addStateService(State $stateService);
    public function addEventService(EventDispatcher $eventService);
    public function setQuantity($quantity = 1);
    public function getQuantity();
    public function setUnitPrice($unitPrice);
    public function getUnitPrice();
    public function getTotal();

}
