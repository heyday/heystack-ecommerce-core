<?php

namespace Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass;

use Heystack\Subsystem\Ecommerce\Services;
use Heystack\Subsystem\Core\DependencyInjection\CompilerPass\HasService;

/**
 * Class HasTransactionService
 * @package Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass
 */
class HasTransactionService extends HasService
{
    /**
     * The name of the service in the container
     * @return string
     */
    protected function getServiceName()
    {
        return Services::TRANSACTION;
    }

    /**
     * The method name used to set the service
     * @return string
     */
    protected function getServiceSetterName()
    {
        return 'setTransaction';
    }
} 