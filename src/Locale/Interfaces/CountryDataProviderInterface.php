<?php

namespace Heystack\Ecommerce\Locale\Interfaces;

/**
 * @package Heystack\Ecommerce\Locale\Interfaces
 */
interface CountryDataProviderInterface
{
    /**
     * Returns the name of the country object
     * @return string
     */
    public function getName();

    /**
     * Returns the country code of the country object
     * @return string
     */
    public function getCountryCode();

    /**
     * Returns a boolean indicating whether this is the default country
     * @return bool
     */
    public function isDefault();
}
