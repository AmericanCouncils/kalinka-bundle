<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use AC\KalinkaBundle\ContainerAwareRoleAuthorizer;
use AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity as Fixtures;

class AnnotatedPropertyTest extends WebTestCase
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

    public function testStudentView()
    {
        // a student shouldn't be able to see ownerName.

        $this->loginAs('student');
        $crawler = $this->client->request('GET', '/document');
        $content = json_decode($this->client->getResponse()->getContent(), true);
        // print_r($content);
        $this->assertFalse(array_key_exists('ownername', $content));
    }

    public function testDifferentViewBasedOnUser()
    {
        $contents = [];
        $this->loginAs('admin');
        $crawler = $this->client->request('GET', '/document');
        $contents[] = json_decode($this->client->getResponse()->getContent(), true);
        $this->loginAs('teacher');
        $crawler = $this->client->request('GET', '/document');
        $contents[] = json_decode($this->client->getResponse()->getContent(), true);
        $this->loginAs('student');
        $crawler = $this->client->request('GET', '/document');
        $contents[] = json_decode($this->client->getResponse()->getContent(), true);

        // these should not all be identical
        $this->assertFalse(
            $contents[0] == $contents[1] &&
            $contents[1] == $contents[2] &&
            $contents[2] == $contents[0]
        );
    }

    // public function testAdminView()
    // {
    //     $this->loginAs('admin');
    // }

    // public function testTeacherView()
    // {
    //     $this->loginAs('teacher');
    // }




}
