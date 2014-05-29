<?php

namespace Heystack\Ecommerce\Locale\Interfaces;

/**
 * @package Heystack\Ecommerce\Locale\Interfaces
 */
interface ZoneDataProviderInterface
{
    /**
     * Returns the name of the Zone object
     * @return string
     */
    public function getName();

    /**
     * An array of strings
     * @return array
     */
    public function getCountryCodes();
}