<?php


namespace Heystack\Subsystem\Ecommerce\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @package Heystack\Subsystem\Ecommerce\Config
 */
class ContainerConfig implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ecommerce');

        $rootNode
            ->children()
                ->arrayNode('currency')->isRequired()
                    ->children()
                        ->scalarNode('default_code')->isRequired()->end()
                        ->floatNode('default_value')->defaultValue(1)->end()
                    ->end()
                ->end()
                ->arrayNode('country')
                    ->children()
                        ->scalarNode('default_code')->isRequired()->end()
                        ->scalarNode('default_name')->isRequired()->end()
                    ->end()
                ->end()
                ->scalarNode('yml_transaction')->end()
            ->end();

        return $treeBuilder;
    }
}