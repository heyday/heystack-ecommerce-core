<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Transaction namespace
 */
namespace Heystack\Ecommerce\Transaction;

use Heystack\Core\State\State;
use Heystack\Core\Storage\Backends\SilverStripeOrm\Backend;
use Heystack\Core\Storage\Storage;
use Heystack\Ecommerce\Currency\CurrencyService;
use Heystack\Ecommerce\Transaction\Events as TransactionEvents;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
     * @var \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface
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
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface               $eventDispatcher
     * @param \Heystack\Core\Storage\Storage                                  $storageService
     * @param \Heystack\Core\State\State                                      $state
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
        return [
            Events::UPDATE => ['onUpdate',0],
            Events::STORE => ['onStore', 0],
            Backend::IDENTIFIER . '.' . TransactionEvents::STORED  => ['onTransactionStored', 0]
        ];
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
        $this->state->removeAll([CurrencyService::ACTIVE_CURRENCY_KEY, 'shipping', 'localeservice', 'loggedInAs']);
    }
}
