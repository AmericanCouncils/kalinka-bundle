<?php

namespace AC\KalinkaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class GuardServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        //register tagged guard services when kalinka.authorizer loads
        $authorizerService = $container->getDefinition('kalinka.authorizer');

        $guardServices = $container->findTaggedServiceIds('kalinka.guard');
        foreach ($guardServices as $id => $tagAttrs) {
            foreach ($tagAttrs as $attrs) {
                $authorizerService->addMethodCall('registerGuardService',[
                    $id,
                    $attrs['tag'],
                    explode(',', $attrs['actions'])
                ]);
            }
        }
    }
}
