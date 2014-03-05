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

            $anonymousRole = 'IS_AUTHENTICATED_ANONYMOUSLY';
            $authRole = 'IS_AUTHENTICATED_FULLY';

            if (isset($args['anonymous_role'])) { $anonymousRole = $args['anonymous_role']; }
            if (isset($args['authenticated_role'])) { $authRole = $args['authenticated_role']; }

            $container->setParameter('kalinka.authorizer.'.$name.'.anonymous_role', $anonymousRole);
            $container->setParameter('kalinka.authorizer.'.$name.'.authenticated_role', $authRole);
        }
    }

    public function getAlias()
    {
        return "ac_kalinka";
    }
}
