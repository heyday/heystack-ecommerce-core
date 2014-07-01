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
 * Provides an implementation for the Serializable Interface for Transaction Modifiers
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
trait TransactionModifierSerializeTrait
{
    use TransactionModifierHasDataTrait;

    /**
     * Returns a serialized string from the data array
     * @return string
     */
    public function serialize()
    {
        return serialize($this->getData());
    }

    /**
     * Unserializes the data into the data array
     * @param string $data
     */
    public function unserialize($data)
    {
        $this->setData(unserialize($data));
    }
}
