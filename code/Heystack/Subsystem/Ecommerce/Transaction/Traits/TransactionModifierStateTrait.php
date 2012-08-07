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
        $this->stateService->setByKey(self::IDENTIFIER, $this->data);
    }

    /**
     * Uses the State service to restore the data array
     */
    public function restoreState()
    {
        $this->data = $this->stateService->getByKey(self::IDENTIFIER);
    }
}
