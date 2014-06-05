<?php

namespace Heystack\Ecommerce\Purchasable\Interfaces;

/**
 * @package Heystack\Ecommerce\Purchasable\Interfaces
 */
interface DiscountAvailabilityInterface
{
    /**
     * @return mixed
     */
    public function isDiscountAvailable();
}