<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Subsystem\Ecommerce\Locale\Interfaces;

/**
 * Defines what methods/functions a Country class needs to implement
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
interface CountryInterface extends \Serializable
{
    /**
     * Returns a unique identifier
     */
    public function getIdentifier();

    /**
     * Returns the name of the country object
     */
    public function getName();

    /**
     * Returns the country code of the country object
     */
    public function getCountryCode();
    
    /**
     * Returns a boolean indicating whether this is the default country
     */
    public function isDefault();

}
