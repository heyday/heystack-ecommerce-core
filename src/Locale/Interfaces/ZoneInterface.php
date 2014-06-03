<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Ecommerce\Locale\Interfaces;

use Heystack\Core\Identifier\IdentifierInterface;

/**
 * Defines what methods/functions a Zone class needs to implement
 *
 * @copyright  Heyday
 * @package Ecommerce-Core
 */
interface ZoneInterface
{
    /**
     * Returns a unique identifier
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier();

    /**
     * Returns the name of the Zone object
     * @return string
     */
    public function getName();

    /**
     * @param IdentifierInterface $identifier
     * @return mixed
     */
    public function addCountry(IdentifierInterface $identifier);

    /**
     * @param array $countries
     * @return mixed
     */
    public function setCountries(array $countries);

    /**
     * @return mixed
     */
    public function getCountries();

    /**
     * @param IdentifierInterface $identifier
     * @return mixed
     */
    public function hasCountry(IdentifierInterface $identifier);

    /**
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getCurrency();
}
