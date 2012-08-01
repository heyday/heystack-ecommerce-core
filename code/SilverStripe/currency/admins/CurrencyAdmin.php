<?php
/**
 * This file is part of the Heystack package
 *
 * @package Ecommerce-Core
 */

/**
 * Model Admin for currency
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 *
 */
class CurrencyAdmin extends ModelAdmin
{

    public static $managed_models = array(
        'EcommerceCurrency'
    );

    public static $url_segment = 'currencies';
    public static $menu_title = 'Currencies';

}
