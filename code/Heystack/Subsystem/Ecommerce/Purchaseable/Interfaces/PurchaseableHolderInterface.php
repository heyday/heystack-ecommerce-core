<?php

namespace Heystack\Subsystem\Ecommerce\Purchaseable\Interfaces;

interface PurchaseableHolderInterface
{

    public function getPurchaseables(array $identifiers = null);

    public function setPurchaseables(array $purchaseables);

    public function addPurchaseable(PurchaseableInterface $purchaseable);

    public function getPurchaseable(array $identifier);

}
