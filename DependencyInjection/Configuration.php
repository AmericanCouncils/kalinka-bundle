<?php

namespace AC\KalinkaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $t = new TreeBuilder();

        $t->root('ac_kalinka')
            ->children()
                ->variableNode('roles')
                    //TODO: real validation of this
                ->end()
                ->arrayNode('authorizers')
                    ->useAttributeAsKey('key')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->arrayNode('objects')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('guard')
                                            ->isRequired()
                                            ->cannotBeEmpty()
                                            ->end()
                                        ->arrayNode('actions')
                                            ->requiresAtLeastOneElement()
                                            ->useAttributeAsKey('key')
                                                ->prototype('scalar')
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->arrayNode('roles')
                                ->useAttributeAsKey('key') // Role
                                ->prototype('variable') // TEMPORARY HACK
                                // TODO: Fix the explicit spec below and use it instead
                                /*
                                 *->prototype('array')
                                 *    ->useAttributeAsKey('key') // Object type
                                 *    ->children()
                                 *        ->prototype('array')
                                 *            ->useAttributeAsKey('key') // Action type
                                 *            ->children()
                                 *                ->prototype('variable') // Policy/policies
                                 *                    ->end()
                                 *                ->end()
                                 *            ->end()
                                 *        ->end()
                                 *    ->end()
                                 *->end()
                                 */
                            ->end()
                        ->end()
                    ->end()
                ->end()
        ;

        return $t;
    }
}
