<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExampleTest extends WebTestCase
{
    
    public function testHelloWorld()
    {
        $client = static::createClient();
        $client->request('GET', '/hello-world');

        $this->assertSame('Hello world!', $client->getResponse()->getContent());
    }

}
