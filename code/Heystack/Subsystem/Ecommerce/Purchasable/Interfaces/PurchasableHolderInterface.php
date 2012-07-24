<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Subsystem\Ecommerce\Purchasable\Interfaces;

use Heystack\Subsystem\Ecommerce\Purchasable\Interfaces\PurchasableInterface;
use \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

/**
 * PurchasableHolderInterface
 *
 * This defines what methods are necessary to implement a Purchasable Holder Class
 *
 * @copyright  Heyday
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
interface PurchasableHolderInterface extends TransactionModifierInterface
{

    /**
     * Returns an array of all the purchasables held by the implementing class
     * @param array $identifiers
     */
    public function getPurchasables(array $identifiers = null);

    /**
     * Sets the purchasables array to be held by the implementing class
     * @param array $purchasables
     */
    public function setPurchasables(array $purchasables);

    /**
     * Add a purchasable to the implementing class
     * @param PurchasableInterface $purchasable
     */
    public function addPurchasable(PurchasableInterface $purchasable, $quantity);

    /**
     * Sets the quantity of the purchasable in the implementing class
     * @param PurchasableInterface $purchasable
     * @param type                 $quantity
     */
    public function setPurchasable(PurchasableInterface $purchasable, $quantity);

    /**
     * Retrieves a purchasable from the implementing class' internal cache of
     * purchasables
     * @param type $identifier
     */
    public function getPurchasable($identifier);

    /**
     * Removes a purchasable from the implementing class' internal cache of
     * purchasables
     * @param type $identifier
     */
    public function removePurchasable($identifier);

    /**
     * Updates the prices of the currently held purchasables
     */
    public function updatePurchasablePrices();

    /**
     * Updates the total based on all purchasables held
     */
    public function updateTotal();

}
