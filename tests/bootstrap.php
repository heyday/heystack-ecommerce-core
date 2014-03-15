<?php

define('UNIT_TESTING', true);
define('ECOMMERCE_CORE_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', ECOMMERCE_CORE_BASE_PATH);

file_put_contents(ECOMMERCE_CORE_BASE_PATH . '/heystack/_manifest_exclude', '');

require_once BASE_PATH . '/framework/core/Constants.php';
require_once BASE_PATH . '/framework/core/Core.php';

if (!file_exists(ECOMMERCE_CORE_BASE_PATH . '/vendor/autoload.php')) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}

