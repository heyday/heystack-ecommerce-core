<?php

define('ECOMMERCE_CORE_BASE_PATH', __DIR__);

\Director::addRules(100, array(
    \EcommerceInputController::$url_segment . '//$Action/$ID' => 'EcommerceInputController'
));
