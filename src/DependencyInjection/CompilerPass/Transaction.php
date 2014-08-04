<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * CompilerPass namespace
 */
namespace Heystack\Ecommerce\DependencyInjection\CompilerPass;

use Heystack\Ecommerce\Services;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Merges extensions definition calls into the container builder.
 *
 * When there exists an extension which defines calls on an existing service,
 * this compiler pass will merge those calls without overwriting.
 *
 * @copyright  Heyday
 * @author     Glenn Bautista
 * @package    Heystack
 */
class Transaction implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::TRANSACTION)) {
            return;

        }

        $definition = $container->getDefinition(Services::TRANSACTION);

        $taggedServices = $container->findTaggedServiceIds(Services::TRANSACTION . '.modifier');

        foreach ($taggedServices as $id => $attributes) {

            $definition->addMethodCall(
                'addModifier',
                [new Reference($id)]
            );

        }

    }
}
