<?php

namespace AC\KalinkaBundle\Tests\Annotations;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AnnotationsTest extends WebTestCase
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

    /**
     * There might be a more direct way to test annotation loading.
     * This calls a route that uses serializer internally, and thus parses the annotations.
     * It should fail if the annotations aren't loaded, but it could also fail for other reasons.
     *
     */
    public function testAnnotationLoading()
    {
        $this->loginAs('student');
        $auth = $this->container->get('kalinka.authorizer');
        $content = $this->client->request('GET', '/document');
        $this->assertTrue(!empty($content));

    }

}
