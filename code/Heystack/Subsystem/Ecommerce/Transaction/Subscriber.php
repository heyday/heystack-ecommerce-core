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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;

use Heystack\Subsystem\Ecommerce\Currency\Events as CurrencyEvents;
use Heystack\Subsystem\Ecommerce\Currency\CurrencyEvent;

use Heystack\Subsystem\Core\Storage\Storage;

/**
 * Handles both subscribing to events and acting on those events needed for Transaction to work properly
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
class Subscriber implements EventSubscriberInterface
{
    /**
     * Holds the Transaction object
     * @var \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface 
     */
    protected $transaction;
    
    /**
     * Holds the EventDispatcher Service object
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface 
     */
    protected $eventDispatcher;
    
    
    protected $storageService;
    
    /**
     * Creates the Susbcriber object
     * @param \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(TransactionInterface $transaction, EventDispatcherInterface $eventDispatcher, Storage $storageService)
    {
        $this->transaction = $transaction;
        $this->eventDispatcher = $eventDispatcher;
        $this->storageService = $storageService;
    }

    /**
     * Returns an array of events to subscribe to and the methods to call when those events are fired
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::UPDATE => array('onUpdate',0),
            Events::STORE => array('onStore', 0),
            CurrencyEvents::CHANGED => array('onCurrencyChange',0)
        );
    }

    /**
     * Method that facilitiates updating the Transaction
     */
    public function onUpdate()
    {
        $this->transaction->updateTotal();
        $this->eventDispatcher->dispatch(Events::UPDATED);
    }
    
    /**
     * Method that facilitates storing the Transaction
     */
    public function onStore() 
    {
        $this->storageService->process($this->transaction);
    }
    
    /**
     * Method that facilitates the updating of the active currency on the Transaction
     * @param \Heystack\Subsystem\Ecommerce\Currency\CurrencyEvent $currencyEvent
     */
    public function onCurrencyChange(CurrencyEvent $currencyEvent)
    {
        $this->transaction->setCurrencyCode($currencyEvent->getCurrency()->CurrencyCode);  
    }

}
