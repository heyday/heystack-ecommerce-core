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

use Heystack\Core\Exception\ConfigurationException;
use Heystack\Core\State\State;
use Heystack\Core\State\StateableInterface;
use Heystack\Core\Storage\Backends\SilverStripeOrm\Backend;
use Heystack\Ecommerce\Currency\CurrencyService;
use Heystack\Ecommerce\Exception\MoneyOverflowException;
use Heystack\Ecommerce\Transaction\Interfaces\HasTransactionInterface;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;
use SebastianBergmann\Money\CurrencyMismatchException;

/**
 * Transaction Service
 *
 * Handles all the TransactionModifiers and calculates the order's total.
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Ecommerce-Core
 */
class Transaction implements TransactionInterface, StateableInterface
{
    /**
     * Holds the key used for storing state
     */
    const IDENTIFIER = 'transaction';

    /**
     * Holds the State service
     * @var \Heystack\Core\State\State
     */
    protected $stateService;

    /**
     * Holds the currency service
     * @var \Heystack\Ecommerce\Currency\CurrencyService
     */
    protected $currencyService;

    /**
     * Holds an array of currently managed TransactionModifiers
     * @var \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    protected $modifiers = [];

    /**
     * @var \SebastianBergmann\Money\Money
     */
    protected $total;

    /**
     * @var string
     */
    protected $status;

    /**
     * Holds an array of statuses that is accepted by the setStatus() method
     * @var array
     */
    protected $validStatuses;

    /**
     * Creates the Transaction object
     * @param State $stateService
     * @param CurrencyService $currencyService
     * @param array $validStatuses
     * @param $defaultStatus
     * @throws ConfigurationException
     */
    public function __construct(
        State $stateService,
        CurrencyService $currencyService,
        array $validStatuses,
        $defaultStatus
    ) {
        $this->stateService = $stateService;
        $this->currencyService = $currencyService;
        $this->validStatuses = $validStatuses;
        if (!$this->isValidStatus($defaultStatus)) {
            throw new ConfigurationException(
                sprintf("The default status '%s' is not a valid status", $defaultStatus)
            );
        }
        $this->status = $defaultStatus;
        $this->total = $this->currencyService->getZeroMoney();
    }

    /**
     * Saves the state of the Transaction object
     */
    public function saveState()
    {
       $this->stateService->setByKey(self::IDENTIFIER, [$this->total, $this->status]);
    }

    /**
     * Restores the state of the Transaction object
     */
    public function restoreState()
    {
        if ($data = $this->stateService->getByKey(self::IDENTIFIER)) {
            list($this->total, $this->status) = $data;
        }
    }

    /**
     * Add a TransactionModifier to the Transaction
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface $modifier
     */
    public function addModifier(TransactionModifierInterface $modifier)
    {
        $this->modifiers[$modifier->getIdentifier()->getFull()] = $modifier;
        if ($modifier instanceof HasTransactionInterface) {
            $modifier->setTransaction($this);
        }
    }

    /**
     * Returns a TransactionModifier based on the identifier
     * @param string $identifier
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface|null
     */
    public function getModifier($identifier)
    {
        return isset($this->modifiers[$identifier]) ? $this->modifiers[$identifier] : null;
    }

    /**
     * Returns all the TransactionModifiers held by the Transaction object
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }

    /**
     * Returns modifiers on the transaction by TranactionModifierType
     * @param  string $type
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getModifiersByType($type)
    {
        $modifiers = [];

        foreach ($this->modifiers as $identifier => $modifier) {
            if ($modifier->getType() === $type) {
                $modifiers[$identifier] = $modifier;
            }
        }

        return $modifiers;
    }

    /**
     * Returns the aggregate total of the TransactionModifers held by the Transaction object
     * @return \SebastianBergmann\Money\Money
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Update the aggregate total of the TransactionModifers held by the Transaction object
     */
    public function updateTotal()
    {
        $this->total = $this->getTotalWithExclusions([]);
        $this->saveState();
    }

    /**
     * Retrieves the total without adding excluded modifiers
     *
     * @param array $exclude an array of identifiers to be excluded
     * @throws \Heystack\Ecommerce\Exception\MoneyOverflowException
     * @return \SebastianBergmann\Money\Money
     */
    public function getTotalWithExclusions(array $exclude)
    {
        $total = $this->currencyService->getZeroMoney();

        foreach ($this->modifiers as $modifier) {
            if (!in_array($modifier->getIdentifier()->getFull(), $exclude)) {
                try {
                    switch ($modifier->getType()) {
                        case TransactionModifierTypes::CHARGEABLE:
                            $operationTotal = $modifier->getTotal();
                            if ($operationTotal->getAmount() + $total->getAmount() > PHP_INT_MAX) {
                                throw new MoneyOverflowException;
                            }
                            $total = $total->add($operationTotal);
                            break;
                        case TransactionModifierTypes::DEDUCTIBLE:
                            $total = $total->subtract($modifier->getTotal());
                            break;
                    }
                } catch (CurrencyMismatchException $e) {
                    // This occurs as the modifiers are updating
                    // in response to currency change events
                    // let it happen and all will be well
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
        return [
            'id' => 'Transaction',
            'flat' => [
                'Total' => $this->total->getAmount() / $this->total->getCurrency()->getSubUnit(),
                'Status' => $this->status,
                'Currency' => $this->currencyService->getActiveCurrencyCode()
            ],
            'related' => []
        ];

    }

    /**
     * Get the type of storage that is being used
     * @return string The type of storage in use
     */
    public function getStorableBackendIdentifiers()
    {
        return [
            Backend::IDENTIFIER
        ];
    }

    /**
     * Sets the status of the transaction
     * @param string $status the status of the transaction
     * @throws \InvalidArgumentException
     */
    public function setStatus($status)
    {
        if ($this->isValidStatus($status)) {
            $this->status = $status;
            $this->saveState();
        } else {
            throw new \InvalidArgumentException(
                sprintf("Status '%s' is not a valid status", $status)
            );
        }
    }

    /**
     * Retrieves the Transaction's status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Checks if a status is valid
     * @param $status
     * @return bool
     */
    protected function isValidStatus($status)
    {
        return in_array($status, $this->validStatuses);
    }
}
