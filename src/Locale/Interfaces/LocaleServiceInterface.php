<?php

namespace Heystack\Ecommerce\Locale\Interfaces;

use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Core\State\StateableInterface;

/**
 * Interface LocaleServiceInterface
 * @package Heystack\Ecommerce\Locale\Interfaces
 */
interface LocaleServiceInterface extends StateableInterface
{
    /**
     * @param IdentifierInterface $identifier
     * @return void
     */
    public function setActiveCountry(IdentifierInterface $identifier);

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\CountryInterface
     */
    public function getActiveCountry();

    /**
     * @param IdentifierInterface $identifier
     * @return \Heystack\Ecommerce\Locale\Interfaces\CountryInterface|null
     */
    public function getCountry(IdentifierInterface $identifier);

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\CountryInterface[]
     */
    public function getCountries();

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\CountryInterface
     */
    public function getDefaultCountry();

    /**
     * @param IdentifierInterface $identifier
     * @return bool
     */
    public function hasCountry(IdentifierInterface $identifier);
}
