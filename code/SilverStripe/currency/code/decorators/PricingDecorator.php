<?php

use Heystack\Subsystem\Core\ServiceStore;

class PricingDecorator extends DataObjectDecorator
{
    
    public function extraStatics()
    {
        
        $db = array();
        
        $currencyService = ServiceStore::getService(\Heystack\Subsystem\Ecommerce\Services::CURRENCY_SERVICE);
        
        foreach ($currencyService->getCurrencies() as $currency) {
            
            $db[$currency->getCurrencyCode()."Price"] = 'Decimal(10, 2)';
            
        }
        
        return array(
            'db' => $db
        );
    }
    
    public function updateCMSFields(\FieldSet &$fields)
    {
        
        $currencyService = ServiceStore::getService(\Heystack\Subsystem\Ecommerce\Services::CURRENCY_SERVICE);
        
        foreach ($currencyService->getCurrencies() as $currency) {
            
            $currencyCode = $currency->getCurrencyCode();
            
            
            $fields->removeByName($currencyCode . "Price");
            
            $fields->addFieldToTab('Root.Prices', new NumericField($currencyCode."Price", $currencyCode . " Price"));
            
        }
        
        return $fields;
    }
    
}