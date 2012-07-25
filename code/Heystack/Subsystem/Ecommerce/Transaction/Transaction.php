<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Transaction namespace
 */
namespace Heystack\Subsystem\Ecommerce\Transaction;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;

use Heystack\Subsystem\Core\Storage\StorableInterface;

/**
 * Transaction's Subscriber
 * 
 * Handles both subscribing to events and acting on those events needed for Transaction work properly
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Ecommerce-Core
 */
class Transaction implements TransactionInterface, StateableInterface, StorableInterface
{
    /**
     * Holds the key used for storing state
     */
    const STATE_KEY = 'transaction';
    
    /**
     * Holds the key used for storing the Total on the data array
     */
    const TOTAL_KEY = 'total';
    
    /**
     * Holds the key use for storing the active currency code on the data array
     */
    const CURRENCY_CODE_KEY = 'currencycode';
    
    /**
     * Holds the State service
     * @var \Heystack\Subsystem\Core\State\State 
     */
    protected $stateService;

    /**
     * Holds an array of currently managed TransactionModifiers
     * @var array
     */
    protected $modifiers = array();
    
    /**
     * Holds all the data that is stored on State
     * @var array
     */
    protected $data = array();
    
    /**
     * Creates the Transaction object
     * @param \Heystack\Subsystem\Core\State\State $stateService
     */
    public function __construct(State $stateService)
    {
        $this->stateService = $stateService;
    }
    
    /**
     * Saves the state of the Transaction object
     */
    public function saveState()
    {
       $this->stateService->setObj(self::STATE_KEY, $this->data);
    }
    
    /**
     * Restores the state of the Transaction object
     */
    public function restoreState()
    {
        $this->data = $this->stateService->getObj(self::STATE_KEY);
    }

    /**
     * Add a TransactionModifier to the Transaction
     * @param \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface $modifier
     */
    public function addModifier(TransactionModifierInterface $modifier)
    {
        $this->modifiers[$modifier->getIdentifier()] = $modifier;
    }
    
    /**
     * Returns a TransactionModifier based on the identifier
     * @param string $identifier
     */
    public function getModifier($identifier)
    {
        return isset($this->modifiers[$identifier]) ? $this->modifiers[$identifier] : null;
    }
    
    /**
     * Returns all the TransactionModifiers held by the Transaction object
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }
    
    /**
     * Returns the aggregate total of the TransactionModifers held by the Transaction object
     */
    public function getTotal()
    {
        $total = isset($this->data[self::TOTAL_KEY]) ? $this->data[self::TOTAL_KEY] : 0;
        
        return number_format($total, 2, '.', '');
    }
    
    /**
     * Update the aggregate total of the TransactionModifers held by the Transaction object
     */
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
    
    /**
     * Returns the currently active currency code
     */
    public function getCurrencyCode()
    {
        return $this->data[self::CURRENCY_CODE_KEY];
    }

    /**
     * Sets the currently active currency code
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {       
        $this->data[self::CURRENCY_CODE_KEY] = $currencyCode;
    }
    
    /**
     * @todo document this
     */
    public function getStorableData()
    {        
        $data = array();
        
        $data['id'] = "Transaction";
        
        $data['flat'] = array(
            'Total' => $this->getTotal(),
            'Status' => 'pending',
            'Currency' => $this->getCurrencyCode()
        );

        
        $data['related'] = array(
            
        );
        
        return $data;
        
    }
    
    /**
     * @todo Document this
     */
    public function getStorageBackendIdentifiers()
    {
        return array(
            'silverstripe_orm'
        );
    }
}
