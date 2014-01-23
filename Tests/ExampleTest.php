<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AC\KalinkaBundle\ContainerAwareRoleAuthorizer;

class ExampleTest extends WebTestCase
{
    protected function getContainer()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        return $kernel->getContainer();
    }

    public function testGetAuthorizerService()
    {
        $authorizer = $this->getContainer()->get('kalinka.authorizer');

        $this->assertTrue($authorizer instanceof ContainerAwareRoleAuthorizer);
    }

}
