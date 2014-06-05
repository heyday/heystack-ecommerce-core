<?php

namespace Heystack\Ecommerce\Purchasable\Interfaces;

/**
 * @package Heystack\Ecommerce\Purchasable\Interfaces
 */
interface DiscountPurchasableInterface
{
    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getDiscountPrice();
}