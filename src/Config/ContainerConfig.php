<?php

namespace Heystack\Ecommerce\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Ecommerce\Config
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
                ->arrayNode('currency')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('code')->isRequired()->end()
                            ->floatNode('value')->defaultValue(1)->end()
                            ->booleanNode('default')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('currency_db')
                    ->children()
                        ->scalarNode('select')->defaultValue('*')->end()
                        ->scalarNode('from')->isRequired()->end()
                        ->scalarNode('where')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('locale')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('code')->isRequired()->end()
                            ->scalarNode('name')->isRequired()->end()
                            ->booleanNode('default')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('locale_db')
                    ->children()
                        ->scalarNode('select')->defaultValue('*')->end()
                        ->scalarNode('from')->isRequired()->end()
                        ->scalarNode('where')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('zone')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->arrayNode('countries')->isRequired()
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->scalarNode('currency')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('zone_db')
                    ->children()
                        ->scalarNode('select')->defaultValue('*')->end()
                        ->scalarNode('from')->isRequired()->end()
                        ->scalarNode('where')->defaultNull()->end()
                    ->end()
                ->end()
                ->scalarNode('yml_transaction')->end()
            ->end();

        return $treeBuilder;
    }
}
