<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;

use Heystack\Subsystem\Ecommerce\Currency\CurrencyService;

class Transaction implements TransactionInterface, StateableInterface
{
    const STATE_KEY = 'transaction';
    const TOTAL_KEY = 'total';
    const CURRENCY_KEY = 'currency';
    
    protected $stateService;

    protected $modifiers = array();
    
    protected $data = array();
    
    public function __construct(State $stateService)
    {
        $this->stateService = $stateService;
    }
    
    public function saveState()
    {
       $this->stateService->setObj(self::STATE_KEY, $this->data);
    }
    
    public function restoreState()
    {
        $this->data = $this->stateService->getObj(self::STATE_KEY);
    }
    
    public function getMerchantReference()
    {
        return 'Merchant Reference';
    }
        
    public function addModifier(TransactionModifierInterface $modifier)
    {
        $this->modifiers[$modifier->getIdentifier()] = $modifier;
    }
    
    public function getModifier($identifier)
    {
        return isset($this->modifiers[$identifier]) ? $this->modifiers[$identifier] : null;
    }
    
    public function getModifiers()
    {
        return $this->modifiers;
    }
    
    public function getTotal()
    {
        $total = isset($this->data[self::TOTAL_KEY]) ? $this->data[self::TOTAL_KEY] : 0;
        
        return number_format($total, 2, '.', '');
    }
    
    public function updateTotal()
    {
        $total = 0;
        
        foreach($this->modifiers as $modifier){
            
            switch($modifier->getType()){
                case TransactionModifierTypes::CHARGEABLE:
                    $total += $modifier->getTotal();
                    break;
                case TransactionModifierTypes::DEDUCTIBLE:
                    $total -= $modifier->getTotal();
                    break;
            }
            
        }
        
        $this->data[self::TOTAL_KEY] = $total;
        
        $this->saveState();
    }
    
    public function getCurrency()
    {
        return $this->data[self::CURRENCY_KEY];
    }
        
    public function updateCurrency($currency) 
    {       
        $this->data[self::CURRENCY_KEY] = $currency;
    }
    
    public function getStorableData()
    {

        \HeydayLog::log($this->getCurrency());
        
        $data = array();
        
        $data['id'] = "Transaction";
        
        $data['flat'] = array(
            'Total' => $this->getTotal(),
            'Status' => 'pending',
            'Currency' => $this->getCurrency()
        );

        
        $data['related'] = array(
            
        );
        
        return $data;
        
    }
    
    public function getStorageIdentifier()
    {
        return 'dataobject';
    }
}
