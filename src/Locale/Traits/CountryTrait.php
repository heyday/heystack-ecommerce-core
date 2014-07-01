<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Traits namespace
 */
namespace Heystack\Ecommerce\Locale\Traits;

use Heystack\Core\GenerateContainerDataObjectTrait;
use Heystack\Core\Identifier\Identifier;

/**
 * Provides a basic implementation of the CountryInterface for dataobjects
 *
 * @copyright  Heyday
 * @author     Glenn Bautista <glenn@heyday.co.nz>
 * @author     Cam Spiers <cameron@heyday.co.nz>
 * @package    Ecommerce-Core
 */
trait CountryTrait
{
    use GenerateContainerDataObjectTrait;

    /**
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->getCountryCode());
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getField('Name');
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->getField('CountryCode');
    }

    /**
     * @return mixed
     */
    public function isDefault()
    {
        return $this->getField('IsDefault');
    }

    /**
     * @param string $field
     * @return mixed
     */
    public abstract function getField($field);
}
