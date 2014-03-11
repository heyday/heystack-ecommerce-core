<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Ecommerce\Purchasable\Interfaces;

use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Core\State\StateableInterface;
use Heystack\Core\Storage\Interfaces\ParentReferenceInterface;
use Heystack\Core\Storage\StorableInterface;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

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
interface PurchasableHolderInterface
    extends
        TransactionModifierInterface,
        ParentReferenceInterface,
        StateableInterface,
        \Serializable,
        StorableInterface
{
    /**
     * Returns an array of all the purchasables held by the implementing class
     * @param array $identifiers
     * @return \Heystack\Ecommerce\Purchasable\Interfaces\PurchasableInterface[]
     */
    public function getPurchasables(array $identifiers = null);

    /**
     * Sets the purchasables array to be held by the implementing class
     * @param \Heystack\Ecommerce\Purchasable\Interfaces\PurchasableInterface[] $purchasables
     */
    public function setPurchasables(array $purchasables);

    /**
     * Add a purchasable to the implementing class
     * @param PurchasableInterface $purchasable
     * @param int                  $quantity
     * @return
     */
    public function addPurchasable(PurchasableInterface $purchasable, $quantity = 1);

    /**
     * Sets the quantity of the purchasable in the implementing class
     * @param PurchasableInterface $purchasable
     * @param int                  $quantity
     */
    public function setPurchasable(PurchasableInterface $purchasable, $quantity);

    /**
     * Retrieves a purchasable from the implementing class' internal cache of
     * purchasables
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     */
    public function getPurchasable(IdentifierInterface $identifier);

    /**
     * Retrieves purchasables from the implementing class' internal cache of
     * purchasables using the Primary string on the Identifier object
     * @param  \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return \Heystack\Ecommerce\Purchasable\Interfaces\PurchasableInterface[]
     */
    public function getPurchasablesByPrimaryIdentifier(IdentifierInterface $identifier);

    /**
     * Removes a purchasable from the implementing class' internal cache of
     * purchasables
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     */
    public function removePurchasable(IdentifierInterface $identifier);

    /**
     * Removes all purchasables from the service
     * @return void
     */
    public function removePurchasables();

    /**
     * Updates the prices of the currently held purchasables
     * @return void
     */
    public function updatePurchasablePrices();

    /**
     * Updates the total based on all purchasables held
     * @param bool $saveState
     * @return void
     */
    public function updateTotal($saveState = true);
}
