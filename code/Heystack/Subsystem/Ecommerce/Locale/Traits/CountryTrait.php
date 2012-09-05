<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Traits namespace
 */
namespace Heystack\Subsystem\Ecommerce\Locale\Traits;

/**
 * Provides a basic implementation of the CountryInterface
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
trait CountryTrait
{
    use \Heystack\Subsystem\Core\State\Traits\DataObjectSerializableTrait;

    public function getIdentifier()
    {
        return $this->CountryCode;
    }

    public function getName()
    {
        return $this->getField('Name');
    }

    public function getCountryCode()
    {
        return $this->getField('CountryCode');
    }

    public function isDefault()
    {
        return $this->getField('IsDefault');
    }

    /**
     * Save all countries to the cache
     */
    public function onAfterWrite()
    {
        $countries = \DataObject::get(__CLASS__);

        $globalState = \Heystack\Subsystem\Core\ServiceStore::getService(\Heystack\Subsystem\Core\Services::STATE_GLOBAL);

        $globalState->setByKey(\Heystack\Subsystem\Ecommerce\Locale\LocaleService::ALL_COUNTRIES_KEY, $countries);

        file_put_contents(
            realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache') . DIRECTORY_SEPARATOR . 'countries.cache',
            serialize($countries)
        );

    }

    /**
     * Save all countries to the cache
     */
    public function onAfterDelete()
    {
        $this->onAfterWrite();
    }
}
