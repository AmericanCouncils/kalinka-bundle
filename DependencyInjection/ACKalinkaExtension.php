<?php

namespace AC\KalinkaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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

        $auth = $config['authorizers']['default'];
        $container->setParameter('kalinka.role_map', $auth['roles']);
        $container->setParameter('kalinka.anonymous_role', $auth['anonymous_role']);
        $container->setParameter('kalinka.authenticated_role', $auth['authenticated_role']);
    }

    public function getAlias()
    {
        return "ac_kalinka";
    }
}
