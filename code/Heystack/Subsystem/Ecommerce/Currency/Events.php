<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Currency namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency;

/**
 * Events definition for Currency
 *
 * Contains all the event constants dispatched by the CurrencyService
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
final class Events
{
    /**
     * Used to denote the event fired whe the active currency is changed
     */
    const CHANGED = 'currency.changed';
}
