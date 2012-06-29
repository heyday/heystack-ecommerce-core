<?php

namespace Heystack\Subsystem\Ecommerce\Purchaseable\Interfaces;

use Heystack\Subsystem\Ecommerce\Purchaseable\Interfaces\PurchaseableInterface;

interface PurchaseableHolderInterface
{

    public function getPurchaseables($identifiers = null);
    public function setPurchaseables(array $purchaseables);
    public function addPurchaseable(PurchaseableInterface $purchaseable);
    public function getPurchaseable($identifier);

}
