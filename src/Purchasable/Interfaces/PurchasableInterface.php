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

use Heystack\Core\State\State;
use Heystack\Core\Storage\Interfaces\ParentReferenceInterface;
use SebastianBergmann\Money\Money;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Purchasable
 *
 * This defines what methods are necessary to implement a Purchasable Class
 *
 * @copyright  Heyday
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
interface PurchasableInterface extends ParentReferenceInterface
{
    /**
     * Returns a unique identifier for the Purchasable object
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier();

    /**
     * Returns the price of the Purchasable object
     * @return \SebastianBergmann\Money\Money
     */
    public function getPrice();

    /**
     * Adds the State service to the Purchasable object
     * @param \Heystack\Core\State\State $stateService
     */
    public function addStateService(State $stateService);

    /**
     * Adds the Event service to the Purchasable object
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventService
     */
    public function addEventService(EventDispatcherInterface $eventService);

    /**
     * Sets the quantity of the Purchasable object in the PurchasableHolder
     * @param int $quantity
     */
    public function setQuantity($quantity = 1);

    /**
     * Returns the quantity of the Purchasable object in the PurchasableHolder
     * @return int
     */
    public function getQuantity();

    /**
     * Sets the price of the Purchasable object in the PurchasableHolder
     * 
     * @param \SebastianBergmann\Money\Money $unitPrice
     */
    public function setUnitPrice(Money $unitPrice);

    /**
     * Returns the price of the Purchasable object in the PurchasableHolder
     * @return \SebastianBergmann\Money\Money
     */
    public function getUnitPrice();

    /**
     * Returns the total price of the Purchasble object in the PurchasableHolder
     * @return \SebastianBergmann\Money\Money
     */
    public function getTotal();
}
