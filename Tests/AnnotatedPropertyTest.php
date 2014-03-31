<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

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

    public function testDifferentDocumentViewBasedOnUser()
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

    public function testStudentDocumentView()
    {
        // a student shouldn't be able to see ownerName.
        $this->loginAs('student');
        $crawler = $this->client->request('GET', '/document');
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(
            [
                'comments' => "Sir Charles is revealed to be a smart, thoughtful and authentic gentleman.",
                'language' => "English",
                'title' => "Sir Charles: The Wit and Wisdom of Charles Barkley",
                'content' => "Known for making news on and off the court, Barkley goes one-one-one with himself in his autobiography, Sir Charles.",
                'datecreated' => 0,
                'datemodified' => 1
            ],
            $content
        );
    }

    public function testAdminView()
    {
        $this->loginAs('admin');
        $crawler = $this->client->request('GET', '/document');
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(
            [
                'ownername' => 'Charles Barkley',
                'comments' => "Sir Charles is revealed to be a smart, thoughtful and authentic gentleman.",
                'language' => "English",
                'title' => "Sir Charles: The Wit and Wisdom of Charles Barkley",
                'content' => "Known for making news on and off the court, Barkley goes one-one-one with himself in his autobiography, Sir Charles.",
                'datecreated' => 0,
                'datemodified' => 1
            ],
            $content
        );
    }


// Robot model tests

    public function testAdminCreatesRobot()
    {
        // admin creates robot, success
        $this->loginAs('admin');
        $requestData = [
            'name' => 'PalBot',
            'make' => 'Pal Industries',
            'model' => '6000',
        ];
        $this->client->request(
            'POST',
            '/robot',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame([
            'name' => 'PalBot',
            'make' => 'Pal Industries',
            'model' => '6000',
            ],
            $content
        );

    }

    public function testAdminCreatesRobotWithFriendlyStatus()
    {
        // admin creates robot including fields he shouldn't be able to set.
        // request succeeds, but extra fields unset.

        $this->loginAs('admin');
        $requestData = [
            'name' => 'PalBot',
            'make' => 'Pal Industries',
            'model' => '6000',
            'friendlytohumans' => true
        ];
        $this->client->request(
            'POST',
            '/robot',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame([
            'name' => 'PalBot',
            'make' => 'Pal Industries',
            'model' => '6000',
            ],
            $content
        );

    }

    public function testStudentCreatesRobot()
    {
        // student creates robot, failure
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException');
        $this->loginAs('student');
        $requestData = [
            'name' => 'PalBot',
            'make' => 'Pal Industries',
            'model' => '6000',
        ];
        $this->client->request(
            'POST',
            '/robot',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);
    }
    public function testTeacherActivatesRobot()
    {
        // teacher activates, success
        $this->loginAs('teacher');
        $this->client->request('POST', '/robot/activate');
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(
            [
                'name' => "Arnold",
                'make' => "Cyberdyne",
                'model' => "T-850",
                'operationalstatus' => "ACTIVE",
                'friendlytohumans' => false
            ],
            $content
        );

    }
    public function testAdminActivatesRobot()
    {
        // admin activates, failure
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException');
        $this->loginAs('admin');
        $this->client->request('POST', '/robot/activate');
        $content = json_decode($this->client->getResponse()->getContent(), true);

    }
    public function testStudentBefriendsRobot()
    {
        // student befriends, success
        $this->loginAs('student');
        $this->client->request('POST', '/robot/befriend');
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(
            [
                'name' => "Arnold",
                'make' => "Cyberdyne",
                'model' => "T-850",
                'operationalstatus' => "ACTIVE",
                'friendlytohumans' => true
            ],
            $content
        );

    }
    public function testTeacherBefriendsRobot()
    {
        // teacher befriends, failure
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException');
        $this->loginAs('teacher');
        $this->client->request('POST', '/robot/befriend');
        $content = json_decode($this->client->getResponse()->getContent(), true);

    }
}
