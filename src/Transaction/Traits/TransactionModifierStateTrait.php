<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Traits namespace
 */
namespace Heystack\Ecommerce\Transaction\Traits;

/**
 * Provides an implementation for the Statable Interface for Transaction Modifiers
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
trait TransactionModifierStateTrait
{
    use TransactionModifierHasDataTrait;

    /**
     * Saves the data array on the State service
     * @return void
     */
    public function saveState()
    {
        $this->getStateService()->setByKey(
            $this->getIdentifier()->getFull(),
            $this->getData()
        );
    }

    /**
     * Uses the State service to restore the data array
     * @return void
     */
    public function restoreState()
    {
        $this->setData(
            $this->getStateService()->getByKey(
                $this->getIdentifier()->getFull()
            )
        );
    }

    /**
     * @return \Heystack\Core\State\State
     */
    abstract public function getStateService();

    /**
     * @return \Heystack\Core\Identifier\IdentifierInterface
     */
    abstract public function getIdentifier();
}
