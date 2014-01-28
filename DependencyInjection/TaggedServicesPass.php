<?php

namespace AC\KalinkaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TaggedServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        //register kalinka.authorize service
        $authorizerService = $container->getDefinition('kalinka.authorizer');

        //TODO: register `kalinka.authorizer` tagged services, setting `kalinka.authorizer` default alias

        //register kalinka.guard services on configured authorizers
        $guardServices = $container->findTaggedServiceIds('kalinka.guard');
        foreach ($guardServices as $id => $tagAttrs) {
            foreach ($tagAttrs as $attrs) {
                $authorizerService->addMethodCall('registerGuardService',[
                    $id,
                    $attrs['tag']
                ]);
            }
        }
    }
}
