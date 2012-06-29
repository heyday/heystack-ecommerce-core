<?php

class EcommerceCurrency extends DataObject 
{
    
    public static $db = array(
      'Price' => 'Money'  
    );
    
    public function getCMSFields() {
        
        $fields = new FieldSet();
        
        // if (!$this->ID) {
        //  $fields->push(new DropdownField('PriceCurrency', 'Currency', Heyday_Silvercart_Currency_Config::getAllowedCurrencies()));
        // } else {
        //  $fields->push(new ReadonlyField('PriceCurrency', 'Currency', $this->PriceCurrency));
        // }
        // $fields->push(new TextField('PriceAmount', 'Amount'));
        
        
        return $fields;
    }
    
}