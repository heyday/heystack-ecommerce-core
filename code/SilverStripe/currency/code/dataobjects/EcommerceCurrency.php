<?php

use \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;

class EcommerceCurrency extends DataObject implements CurrencyInterface, Serializable
{
    use Heystack\Subsystem\Products\Product\DataObjectTrait;

    public static $db = array(
        'Name' => 'Varchar(255)',
        'Value' => 'Decimal(10,3)',
        'Symbol' => 'Varchar(255)',
        'IsDefaultCurrency' => 'Boolean'
    );
    
    static $summary_fields = array(
        'Name',
        'Symbol',
        'Value',
        'IsDefaultCurrency'
    );

    public function retrieveSymbol()
    {
        return $this->Symbol;
    }
    
    public function isDefaultCurrency()
    {
        return $this->IsDefaultCurrency;
    }
    
    public function retrieveValue()
    {
        return $this->Value;
    }

}
