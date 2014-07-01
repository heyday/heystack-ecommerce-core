<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Traits namespace
 */
namespace Heystack\Ecommerce\Currency\Traits;

use Heystack\Core\GenerateContainerDataObjectTrait;
use Heystack\Core\Identifier\Identifier;

/**
 * Provides a basic implementation of the CurrencyInterface for dataobjects
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Core
 */
trait CurrencyTrait
{

    /**
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->getCurrencyCode());
    }

    /**
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->getField('CurrencyCode');
    }

    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->getField('Symbol');
    }

    /**
     * @return mixed
     */
    public function isDefaultCurrency()
    {
        return $this->getField('IsDefaultCurrency');
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->getField('Value');
    }

    /**
     * @param string $field
     * @return mixed
     */
    public abstract function getField($field);
}
