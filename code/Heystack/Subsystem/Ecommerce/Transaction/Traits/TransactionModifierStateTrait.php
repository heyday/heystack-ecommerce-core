<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Traits namespace
 */
namespace Heystack\Subsystem\Ecommerce\Transaction\Traits;

use Heystack\Subsystem\Core\State\State;

/**
 * Provides an implementation for the Statable Interface for Transaction Modifiers
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
trait TransactionModifierStateTrait
{
    /**
     * Stores data for state
     * @var array
     */
    protected $data;
    /**
     * @var \Heystack\Subsystem\Core\State\State
     */
    protected $stateService;
    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {
        $this->getStateService()->setByKey(
            $this->getIdentifier()->getFull(),
            $this->data
        );
    }
    /**
     * Uses the State service to restore the data array
     */
    public function restoreState()
    {
        $this->data = $this->getStateService()->getByKey(
            $this->getIdentifier()->getFull()
        );
    }
    /**
     * @throws \RuntimeException
     */
    public function getStateService()
    {
        if (!$this->stateService instanceof State) {
            throw new \RuntimeException('To use TransactionModifierStateTrait a stateService must be available');
        }
        return $this->stateService;
    }
    /**
     * @throws \RuntimeException
     */
    public function getIdentifier()
    {
        throw new \RuntimeException(
            'To use TransactionModifierStateTrait a getIdentifier method must be provided on the using class'
        );
    }
}
