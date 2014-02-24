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

use Heystack\Core\State\State;

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
     * Saves the data array on the State service
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
     */
    public function restoreState()
    {
        $this->setData(
            $this->getStateService()->getByKey(
                $this->getIdentifier()->getFull()
            )
        );
    }
}
