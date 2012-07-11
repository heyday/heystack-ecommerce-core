<?php

use \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;

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

    public function getSymbol()
    {
        return $this->record['Symbol'];
    }

    public function isDefaultCurrency()
    {
        return $this->IsDefaultCurrency;
    }

    public function getValue()
    {
        return $this->record['Value'];
    }

    public function getCurrencyCode()
    {
        return $this->record['CurrencyCode'];
    }

    public function getIdentifier()
    {
        return $this->getCurrencyCode();
    }

}
