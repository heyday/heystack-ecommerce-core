<?php

define('UNIT_TESTING', true);
define('ECOMMERCE_CORE_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', ECOMMERCE_CORE_BASE_PATH);

// Set up required environment for container generation
if (!file_exists(BASE_PATH . '/mysite/')) {
    mkdir(BASE_PATH . '/mysite/config/', 0777, true);
    mkdir(BASE_PATH . '/mysite/_config/', 0777, true);
    foreach (glob(BASE_PATH.'/_config/*') as $file) {
        copy($file, BASE_PATH.'/mysite/_config/'.basename($file));
    }
}

file_put_contents(BASE_PATH . '/mysite/config/services_dev.yml', '');
file_put_contents(BASE_PATH . '/heystack/_manifest_exclude', '');

require_once BASE_PATH . '/framework/core/Constants.php';
require_once BASE_PATH . '/framework/core/Core.php';

if (!file_exists(ECOMMERCE_CORE_BASE_PATH . '/vendor/autoload.php')) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}
