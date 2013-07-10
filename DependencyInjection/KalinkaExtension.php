<?php

namespace AC\KalinkaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class KalinkaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach($config['authorizers'] as $name => $authConf) {
            $def = new Definition(
                'AC\KalinkaBundle\AuthorizerContainer',
                [
                    $authConf,
                    new Reference('kalinka.authorizer_factory'),
                    new Reference('security.context'),
                    new Reference('service_container')
                ]
            );
            $container->setDefinition("kalinka.authorizer_containers.$name", $def);
            if ($name == "default") {
                $container->setAlias("kalinka", "kalinka.authorizer_containers.default");
            }
        }
    }

    public function getAlias()
    {
        return "kalinka";
    }
}
