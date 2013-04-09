<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * CompilerPass namespace
 */
namespace Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

use Heystack\Subsystem\Ecommerce\Services;

/**
 * @copyright  Heyday
 * @author     Cam Spiers
 * @package    Heystack
 */
class Currency implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::CURRENCY_SERVICE)) {
            return;
        }

        $currencies = array();

        foreach ($container->findTaggedServiceIds(Services::CURRENCY_SERVICE . '.currency') as $id => $tags) {
            foreach ($tags as $tag) {
                $currencies[] = new Reference($id);
            }
        }

        if (count($currencies) == 0) {
            throw new \RuntimeException('At least one currency must be configured');
        }

        $defaultCurrency = reset($currencies);

        foreach ($container->findTaggedServiceIds(Services::CURRENCY_SERVICE . '.currency_default') as $id => $tags) {
            foreach ($tags as $tag) {
                $defaultCurrency = new Reference($id);
            }
        }

        $container->getDefinition(Services::CURRENCY_SERVICE)->replaceArgument(0, $currencies);
        $container->getDefinition(Services::CURRENCY_SERVICE)->replaceArgument(1, $defaultCurrency);

    }
}
