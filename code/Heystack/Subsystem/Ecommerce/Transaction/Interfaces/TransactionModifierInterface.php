<?php

namespace Heystack\Subsystem\Ecommerce\Transaction\Interfaces;

interface TransactionModifierInterface
{
    public function getIdentifier();
    public function getTotal();
    public function getUpdateEventString();
}