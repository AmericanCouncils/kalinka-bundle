<?php

namespace AC\KalinkaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class ACKalinkaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['authorizers'] as $name => $args) {
            $container->setParameter('kalinka.authorizer.'.$name.'.role_map', $args['roles']);
            $container->setParameter('kalinka.authorizer.'.$name.'.anonymous_role', $args['anonymous_role']);
            $container->setParameter('kalinka.authorizer.'.$name.'.authenticated_role', $args['authenticated_role']);
        }
    }

    public function getAlias()
    {
        return "ac_kalinka";
    }
}
