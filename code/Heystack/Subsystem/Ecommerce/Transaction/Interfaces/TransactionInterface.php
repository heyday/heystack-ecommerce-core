<?php

namespace Heystack\Subsystem\Ecommerce\Transaction\Interfaces;

interface TransactionInterface
{
    public function addModifier(TransactionModifierInterface $modifier);
    public function getModifier($identifier);
    public function getModifiers();
    public function getTotal();
    public function updateTotal();
    public function getUpdateEventStrings();
}