<?php

namespace Heystack\Ecommerce\Purchasable\Interfaces;

/**
 * @package Heystack\Ecommerce\Purchasable\Interfaces
 */
interface AvailabilityInterface
{
    /**
     * @return mixed
     */
    public function isAvailable();
}