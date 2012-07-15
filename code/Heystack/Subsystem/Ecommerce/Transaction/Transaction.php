<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;

class Transaction implements TransactionInterface, StateableInterface
{
    const STATE_KEY = 'transaction';
    const TOTAL_KEY = 'total';
    
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
        return isset($this->data[self::TOTAL_KEY]) ? $this->data[self::TOTAL_KEY] : 0;
    }
    
    public function updateTotal()
    {
        $total = 0;
        
        foreach($this->modifiers as $modifier){
            $total += $modifier->getTotal();
        }
        
        $this->data[self::TOTAL_KEY] = $total;
        
        $this->saveState();
    }
}
