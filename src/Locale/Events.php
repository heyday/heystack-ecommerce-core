<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Locale namespace
 */
namespace Heystack\Ecommerce\Locale;

/**
 * Events holds constant references to triggerable dispatch events.
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 * @see Symfony\Component\EventDispatcher
 *
 */
final class Events
{
    /**
     * Indicates that the active country/locale has changed.
     */
    const CHANGED = 'locale.changed';
}
