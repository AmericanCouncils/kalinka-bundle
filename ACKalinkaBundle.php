<?php

namespace AC\KalinkaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AC\KalinkaBundle\DependencyInjection\KalinkaExtension;

class ACKalinkaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerExtension(new KalinkaExtension());
    }
}
