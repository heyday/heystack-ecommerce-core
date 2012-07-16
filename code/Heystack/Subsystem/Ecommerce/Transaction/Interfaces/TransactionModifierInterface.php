<?php

namespace Heystack\Subsystem\Ecommerce\Transaction\Interfaces;

interface TransactionModifierInterface
{
    public function getIdentifier();
    public function getTotal();
    
    /**
     * Indicates the type of amount the modifier will return
     * Must return a constant from TransactionModifierTypes
     */
    public function getType();
}