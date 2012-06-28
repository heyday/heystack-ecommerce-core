<?php

interface PurchaseableHolderInterface 
{
    
    public function getPurchaseables(array $identifier = null);
    
    public function setPurchaseables(array $purchaseables);
    
    public function addPurchaseable(PurchaseableInterface $purchaseable);
    
    public function getPurchaseable(array $identifier);
    
}