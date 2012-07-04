<?php

namespace Heystack\Subsystem\Ecommerce\Purchasable\Interfaces;

use Heystack\Subsystem\Ecommerce\Purchasable\Interfaces\PurchasableInterface;

interface PurchasableHolderInterface
{

    public function getPurchasables($identifiers = null);
    public function setPurchasables(array $purchasables);
    public function addPurchasable(PurchasableInterface $purchasable);
    public function getPurchasable($identifier);
    public function removePurchasable($identifier);

}
