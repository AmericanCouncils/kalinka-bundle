<?php

namespace AC\KalinkaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AC\KalinkaBundle\DependencyInjection\TaggedServicesPass;

class ACKalinkaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        //find tagged guard services registered by app or other bundles
        $container->addCompilerPass(new TaggedServicesPass());
    }
}
