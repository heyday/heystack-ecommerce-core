<?php

namespace Heystack\Subsystem\Ecommerce\Purchaseable\Interfaces;

use Heystack\Subsystem\Core\State\State;

use Symfony\Component\EventDispatcher\EventDispatcher;

interface PurchaseableInterface
{

    public function getIdentifier();
    public function getPrice();
    public function addStateService(State $stateService);
    public function addEventDispatcher(EventDispatcher $eventDispatcher);

}
