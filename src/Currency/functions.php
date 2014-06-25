<?php

namespace Heystack\Ecommerce\Currency;

use SebastianBergmann\Money\Money;

/**
 * Makes a money object safely serializable
 * @param \SebastianBergmann\Money\Money $money
 * @return \SebastianBergmann\Money\Money
 */
function getSerializableMoney(Money $money)
{
    return new Money(
        $money->getAmount(),
        clone $money->getCurrency()
    );
}