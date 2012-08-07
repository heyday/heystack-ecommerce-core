<?php
/**
 * This file is part of the Heystack package
 *
 * @package Ecommerce-Core
 */
use \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;

use Heystack\Subsystem\Core\ServiceStore;
use Heystack\Subsystem\Ecommerce\Currency\CurrencyService;
/**
 * EcommerceCurrency is the base currency object. Allows for multiple currencies
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 *
 */
class EcommerceCurrency extends DataObject implements CurrencyInterface, Serializable
{
    use Heystack\Subsystem\Products\Product\DataObjectTrait;

    public static $db = array(
        'CurrencyCode' => 'Varchar(255)',
        'Value' => 'Decimal(10,3)',
        'Symbol' => 'Varchar(255)',
        'IsDefaultCurrency' => 'Boolean'
    );

    public static $summary_fields = array(
        'CurrencyCode',
        'Symbol',
        'Value',
        'IsDefaultCurrency'
    );

    /**
     * Get the currencies symbol (£, $ etc)
     * @return string
     */
    public function getSymbol()
    {
        return $this->record['Symbol'];
    }

    /**
     * Get whether this is the default currency
     * @return boolean
     */
    public function isDefaultCurrency()
    {
        return $this->IsDefaultCurrency;
    }

    /**
     * Get the currencies value against the base currency. Used in conversions.
     * @return float
     */
    public function getValue()
    {
        return $this->record['Value'];
    }

    /**
     * Get the currencies ISO_4217 code
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->record['CurrencyCode'];
    }

    /**
     * Return the identifier for this currency
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getCurrencyCode();
    }

    /**
     * Save all currencies to the cache
     */
    public function onAfterWrite()
    {
        $currencies = DataObject::get('EcommerceCurrency');
        
        $globalState = ServiceStore::getService(\Heystack\Subsystem\Core\Services::BACKEND_GLOBAL_MEMCACHE);
        
        $globalState->setByKey(CurrencyService::ALL_CURRENCIES_KEY, $currencies);
        
        file_put_contents(
            realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache') . DIRECTORY_SEPARATOR . 'currency.cache',
            serialize($currencies)
        );
        
    }
}
