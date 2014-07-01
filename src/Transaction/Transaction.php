<?php

namespace Heystack\Ecommerce\Transaction;

use Heystack\Core\Exception\ConfigurationException;
use Heystack\Core\State\State;
use Heystack\Core\State\StateableInterface;
use Heystack\Core\Storage\Backends\SilverStripeOrm\Backend;
use Heystack\Core\Traits\HasStateServiceTrait;
use Heystack\Ecommerce\Currency\CurrencyService;
use Heystack\Ecommerce\Currency\Traits\HasCurrencyServiceTrait;
use Heystack\Ecommerce\Transaction\Interfaces\HasLinkedTransactionModifiersInterface;
use Heystack\Ecommerce\Transaction\Interfaces\HasTransactionInterface;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

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
    use HasStateServiceTrait;
    use HasCurrencyServiceTrait;

    /**
     * Holds the key used for storing state
     */
    const IDENTIFIER = 'transaction';

    /**
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
     * Tracks if a update has been requested
     * @var bool
     */
    protected $updateRequested;

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
    )
    {
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
        $this->stateService->setByKey(
            self::IDENTIFIER,
            [
                $this->total,
                $this->status,
                $this->updateRequested
            ]
        );
    }

    /**
     * Restores the state of the Transaction object
     */
    public function restoreState()
    {
        if ($data = $this->stateService->getByKey(self::IDENTIFIER)) {
            list($this->total, $this->status, $this->updateRequested) = $data;
        }
    }

    /**
     * Add a TransactionModifier to the Transaction
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface $modifier
     * @throws \InvalidArgumentException
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
        $modifiers = $this->getModifiers();
        return isset($modifiers[$identifier]) ? $modifiers[$identifier] : null;
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
     * @throws \InvalidArgumentException
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getModifiersByType($type)
    {
        $modifiers = [];

        foreach ($this->modifiers as $modifier) {
            if ($modifier->getType() === $type) {
                $modifiers[$modifier->getIdentifier()->getFull()] = $modifier;
            }
        }

        return $modifiers;
    }

    /**
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getChargeableModifiers()
    {
        return $this->getModifiersByType(TransactionModifierTypes::CHARGEABLE);
    }

    /**
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getDeductibleModifiers()
    {
        return $this->getModifiersByType(TransactionModifierTypes::DEDUCTIBLE);
    }

    /**
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getNeutralModifiers()
    {
        return $this->getModifiersByType(TransactionModifierTypes::NEUTRAL);
    }

    /**
     * Returns the aggregate total of the TransactionModifers held by the Transaction object
     * @return \SebastianBergmann\Money\Money
     */
    public function getTotal()
    {
        if ($this->updateRequested) {
            $this->total = $this->getTotalWithExclusions([]);
            $this->updateRequested = false;
            $this->saveState();
        }

        return $this->total;
    }


    /**
     * Update the aggregate total of the TransactionModifers held by the Transaction object
     * @return void
     */
    public function updateTotal()
    {
        $this->updateRequested = true;
        $this->saveState();
    }

    /**
     * Retrieves the total excluding specified modifiers
     *
     * @param array $exclude an array of identifiers to be excluded
     * @throws \SebastianBergmann\Money\OverflowException
     * @throws \RuntimeException
     * @return \SebastianBergmann\Money\Money
     */
    public function getTotalWithExclusions(array $exclude)
    {
        return $this->getChargeableTotalWithExclusions($exclude)
            ->subtract($this->getDeductibleTotalWithExclusions($exclude));
    }

    /**
     * @param array $exclude
     * @return \SebastianBergmann\Money\Money
     */
    public function getChargeableTotalWithExclusions(array $exclude)
    {
        $total = $this->currencyService->getZeroMoney();

        foreach ($this->getChargeableModifiers() as $chargeableModifier) {
            // Exclude specified modifiers
            if (in_array($chargeableModifier->getIdentifier()->getFull(), $exclude)) {
                continue;
            }

            $total = $total->add($chargeableModifier->getTotal());
        }

        return $total;
    }

    /**
     * @param array $exclude
     * @return \SebastianBergmann\Money\Money
     */
    public function getDeductibleTotalWithExclusions(array $exclude)
    {
        $deductibleTotal = $this->currencyService->getZeroMoney();

        foreach ($this->getChargeableModifiers() as $chargeableModifier) {
            // Exclude specified modifiers
            if (in_array($chargeableModifier->getIdentifier()->getFull(), $exclude)) {
                continue;
            }

            $chargeableModifierTotal = $chargeableModifier->getTotal();
            $discountSubTotal = $this->currencyService->getZeroMoney();

            foreach ($this->getLinkedModifers($chargeableModifier, $this->getDeductibleModifiers()) as $discountModifier) {
                if (in_array($discountModifier->getIdentifier()->getFull(), $exclude)) {
                    continue;
                }

                $discountSubTotal = $discountSubTotal->add($discountModifier->getTotal());
            }

            if ($discountSubTotal->greaterThan($chargeableModifierTotal)) {
                $deductibleTotal = $deductibleTotal->add($chargeableModifierTotal);
            } else {
                $deductibleTotal = $deductibleTotal->add($discountSubTotal);
            }
        }

        return $deductibleTotal;
    }

    /**
     * @param Interfaces\TransactionModifierInterface $modifier
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]|void $fromModifiers
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getLinkedModifers(TransactionModifierInterface $modifier, array $fromModifiers = null)
    {
        $linkedModifiers = [];

        $fromModifiers = ($fromModifiers ? : $this->getModifiers());

        foreach ((array)$fromModifiers as $fromModifier) {
            if (
                $fromModifier instanceof HasLinkedTransactionModifiersInterface &&
                in_array($modifier, $fromModifier->getLinkedModifiers(), true)
            ) {
                $linkedModifiers[] = $fromModifier;
            }
        }

        return $linkedModifiers;
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
