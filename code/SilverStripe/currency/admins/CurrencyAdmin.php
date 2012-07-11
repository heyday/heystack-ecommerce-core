<?php

class CurrencyAdmin extends ModelAdmin
{

    public static $managed_models = array(
        'EcommerceCurrency'
    );

    public static $url_segment = 'currencies';
    public static $menu_title = 'Currencies';

}
