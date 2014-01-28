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
                ->scalarNode('default_authorizer')->defaultValue('default')->end()
                ->variableNode('authorizers')
                    //TODO: real validation of this
                ->end()
            ->end()
        ;

        return $t;
    }
}
