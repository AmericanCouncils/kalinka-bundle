<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class KalinkaAuthorizationSubscriberTest extends WebTestCase
{
    protected $container;
    protected $client;

    /**
     * Populates container as it would be during a request from a specific user.
     */
    protected function loginAs($name = null, $pass = null)
    {
        $pass = $pass ? $pass : $name;

        $this->client = $client = static::createClient([], [
            'PHP_AUTH_USER' => $name,
            'PHP_AUTH_PW' => $pass
        ]);

        //test request forces container into "request" scope with
        //a populated security.context token
        $this->client->request('GET', '/hello-world');

        $this->container = $client->getContainer();
    }

    public function testNothing()
    {
        $this->assertTrue(true);
    }
}
