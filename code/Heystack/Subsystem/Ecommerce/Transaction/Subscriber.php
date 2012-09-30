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

use Heystack\Subsystem\Ecommerce\Transaction\Events as TransactionEvents;

use Heystack\Subsystem\Ecommerce\Currency\Events as CurrencyEvents;
use Heystack\Subsystem\Ecommerce\Currency\Event\CurrencyEvent;
use Heystack\Subsystem\Ecommerce\Currency\CurrencyService;

use Heystack\Subsystem\Shipping\Output\Processor as ShippingService;

use Heystack\Subsystem\Core\Storage\Storage;
use Heystack\Subsystem\Core\Storage\Backends\SilverStripeOrm\Backend;

use Heystack\Subsystem\Core\State\State;

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
    
    protected $state;

    /**
     * Creates the Susbcriber object
     * @param \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface               $eventDispatcher
     */
    public function __construct(TransactionInterface $transaction, EventDispatcherInterface $eventDispatcher, Storage $storageService, State $state)
    {
        $this->transaction = $transaction;
        $this->eventDispatcher = $eventDispatcher;
        $this->storageService = $storageService;
        $this->state = $state;
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
            Backend::IDENTIFIER . '.' . TransactionEvents::STORED  => array('onTransactionStored', 0)
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
     * Called after the Transaction is stored, clears state apart from the 
     * active currency.
     */
    public function onTransactionStored()
    {
        $this->state->removeAll(array(CurrencyService::IDENTIFIER, 'shipping', 'localeservice', 'loggedInAs'));
    }

   

}
