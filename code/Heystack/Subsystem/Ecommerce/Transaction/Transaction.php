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

use Heystack\Subsystem\Core\Exception\ConfigurationException;
use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;
use Heystack\Subsystem\Core\Storage\Backends\SilverStripeOrm\Backend;
use Heystack\Subsystem\Core\Storage\StorableInterface;
use Heystack\Subsystem\Core\ViewableData\ViewableDataInterface;
use Heystack\Subsystem\Ecommerce\Currency\CurrencyService;
use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

/**
 * Transaction Service
 *
 * Handles all the TransactionModifiers and calculates the order's total.
 * Also holds the collator for displaying data
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Ecommerce-Core
 */
class Transaction implements TransactionInterface, StateableInterface, StorableInterface
{
    /**
     * Holds the key used for storing state
     */
    const IDENTIFIER = 'transaction';

    /**
     * Holds the key used for storing the Total on the data array
     */
    const TOTAL_KEY = 'total';

    /**
     * Holds the key used for storing the active currency code on the data array
     */
    const CURRENCY_CODE_KEY = 'currencycode';

    /**
     * Holds the key used for storing the status of the transaction
     */
    const STATUS_KEY = 'status';

    /**
     * Holds the State service
     * @var \Heystack\Subsystem\Core\State\State
     */
    protected $stateService;

    /**
     * Holds the currency service
     * @var \Heystack\Subsystem\Ecommerce\Currency\CurrencyService
     */
    protected $currencyService;

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
     * The classname to be used to instantiate the Collator
     * @var string
     */
    protected $collatorClassName;

    /**
     * Holds the Collator object
     * @var \Heystack\Subsystem\Ecommerce\Transaction\Collator
     */
    protected $collator;

    /**
     * Holds an array of statuses that is accepted by the setStatus() method
     * @var array
     */
    protected $validStatuses;

    /**
     * Holds the default status of the transaction
     * @var string
     */
    protected $defaultStatus;

    /**
     * Creates the Transaction object
     * @param \Heystack\Subsystem\Core\State\State $stateService
     */
    public function __construct(
        State $stateService,
        $collatorClassName,
        CurrencyService $currencyService,
        array $validStatuses,
        $defaultStatus
    ) {
        $this->stateService = $stateService;

        if (class_exists($collatorClassName) && in_array(
                'Heystack\Subsystem\Core\ViewableData\ViewableDataInterface',
                class_implements($collatorClassName)
            )
        ) {
            $this->collatorClassName = $collatorClassName;
        } else {
            throw new ConfigurationException(
                $collatorClassName .
                ' does not exist or does not implement Heystack\Subsystem\Core\ViewableData\ViewableDataInterface'
            );
        }

        $this->currencyService = $currencyService;

        $this->validStatuses = $validStatuses;

        $this->defaultStatus = $defaultStatus;
    }

    /**
     * Saves the state of the Transaction object
     */
    public function saveState()
    {
       $this->stateService->setByKey(self::IDENTIFIER, $this->data);
    }

    /**
     * Restores the state of the Transaction object
     */
    public function restoreState()
    {
        $this->data = $this->stateService->getByKey(self::IDENTIFIER);
    }

    /**
     * Add a TransactionModifier to the Transaction
     * @param \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface $modifier
     */
    public function addModifier(TransactionModifierInterface $modifier)
    {
        $this->modifiers[$modifier->getIdentifier()->getFull()] = $modifier;
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
     * Returns modifiers on the transaction by TranactionModifierType
     * @param  string $type
     * @return array
     */
    public function getModifiersByType($type)
    {

        $modifiers = array();

        foreach ($this->modifiers as $identifier => $modifier) {

            if ($modifier->getType() == $type) {

                $modifiers[$identifier] = $modifier;

            }

        }

        return $modifiers;

    }

    /**
     * Returns the aggregate total of the TransactionModifers held by the Transaction object
     */
    public function getTotal()
    {
        return isset($this->data[self::TOTAL_KEY]) ? $this->data[self::TOTAL_KEY] : 0;
    }

    /**
     * Update the aggregate total of the TransactionModifers held by the Transaction object
     */
    public function updateTotal()
    {
        $this->data[self::TOTAL_KEY] = $this->getTotalWithExclusions(array());

        $this->saveState();
    }

    /**
     * Retrieves the total without adding excluded modifiers
     * @param array $exclude an array of identifiers to be excluded
     */
    public function getTotalWithExclusions(array $exclude)
    {
        $total = 0;

        foreach ($this->modifiers as $modifier) {

            if (!in_array($modifier->getIdentifier()->getFull(), $exclude)) {

                switch ($modifier->getType()) {
                    case TransactionModifierTypes::CHARGEABLE:
                        $total += $modifier->getTotal();
                        break;
                    case TransactionModifierTypes::DEDUCTIBLE:
                        $total -= $modifier->getTotal();
                        break;
                }

            }

        }

        return $total;
    }

    /**
     * Get the identifier for this system
     * @return string
     */
    public function getStorableIdentifier()
    {
        return self::IDENTIFIER;

    }

    /**
     * Get the name of the schema this system relates to
     * @return string
     */
    public function getSchemaName()
    {
        return 'Transaction';

    }

    /**
     * Get the data to store
     * @return array The data to store
     */
    public function getStorableData()
    {
        return array(
            'id' => 'Transaction',
            'flat' => array(
                'Total' => $this->getTotal(),
                'Status' => $this->getStatus(),
                'Currency' => $this->currencyService->getActiveCurrencyCode()
            ),
            'related' => array()
        );

    }

    /**
     * Get the type of storage that is being used
     * @return string The type of storage in use
     */
    public function getStorableBackendIdentifiers()
    {
        return array(
            Backend::IDENTIFIER
        );

    }

    /**
     * Get collator for the transaction, using a classname
     *
     * @return type
     * @throws ConfigurationException
     */
    public function getCollator()
    {
        if (!$this->collator) {

            $this->collator = new $this->collatorClassName($this, $this->currencyService);

        }

        return $this->collator;
    }

    /**
     * Sets the status of the transaction
     * @param string $status the status of the transaction
     */
    public function setStatus($status)
    {

        if (in_array($status, $this->validStatuses)) {

            $this->data[self::STATUS_KEY] = $status;

            $this->saveState();
        }

    }

    /**
     * Retrieves the Transaction's status
     */
    public function getStatus()
    {
        return isset($this->data[self::STATUS_KEY]) ? $this->data[self::STATUS_KEY] : $this->defaultStatus;

    }
}
