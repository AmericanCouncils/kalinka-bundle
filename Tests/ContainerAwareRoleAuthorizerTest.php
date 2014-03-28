<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use AC\KalinkaBundle\ContainerAwareRoleAuthorizer;
use AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity as Fixtures;

class ContainerAwareRoleAuthorizerTest extends WebTestCase
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

    public function testGetAuthorizerService()
    {
        $this->loginAs('admin');
        $authorizer = $this->container->get('kalinka.authorizer');

        $this->assertTrue($authorizer instanceof ContainerAwareRoleAuthorizer);
    }

    public function testCallAuthorizerAsAdmin()
    {
        $this->loginAs('admin');
        $auth = $this->container->get('kalinka.authorizer');

        $this->assertTrue($auth->can('foo', 'system'));
        $this->assertTrue($auth->can('bar', 'system'));
        $this->assertTrue($auth->can('baz', 'system'));

        $doc = Fixtures\Document::createFromArray([
            'ownerName' => 1,
            'title' => 'Foo',
            'content' => "Barzinbaz",
            'locked' => true
        ]);

        $this->assertTrue($auth->can('index', 'document', $doc));
        $this->assertTrue($auth->can('create', 'document', $doc));
        $this->assertTrue($auth->can('read', 'document', $doc));
        $this->assertTrue($auth->can('update', 'document', $doc));
        $this->assertTrue($auth->can('delete', 'document', $doc));
    }

    public function testCallAuthorizerAsTeacher()
    {
        $this->loginAs('teacher');
        $auth = $this->container->get('kalinka.authorizer');

        $this->assertTrue($auth->can('foo', 'system'));
        $this->assertFalse($auth->can('bar', 'system'));
        $this->assertFalse($auth->can('baz', 'system'));

        //owner, but locked
        $doc = Fixtures\Document::createFromArray([
            'ownerName' => 'teacher',
            'locked' => true
        ]);
        $this->assertTrue($auth->can('index', 'document', $doc));
        $this->assertTrue($auth->can('create', 'document', $doc));
        $this->assertTrue($auth->can('read', 'document', $doc));
        $this->assertFalse($auth->can('update', 'document', $doc));
        $this->assertFalse($auth->can('delete', 'document', $doc));

        //owner and unlocked
        $doc = Fixtures\Document::createFromArray([
            'ownerName' => 'teacher',
            'locked' => false
        ]);
        $this->assertTrue($auth->can('update', 'document', $doc));
        $this->assertTrue($auth->can('delete', 'document', $doc));

        //unlocked, but not owner
        $doc = Fixtures\Document::createFromArray([
            'ownerName' => 'Foobert',
            'locked' => false
        ]);
        $this->assertFalse($auth->can('update', 'document', $doc));
        $this->assertFalse($auth->can('delete', 'document', $doc));
    }

    public function testCallAuthorizerAsStudent()
    {
        $this->loginAs('student');
        $auth = $this->container->get('kalinka.authorizer');

        $this->assertFalse($auth->can('foo', 'system'));
        $this->assertFalse($auth->can('bar', 'system'));
        $this->assertFalse($auth->can('baz', 'system'));

        $doc = Fixtures\Document::createFromArray([
            'ownerName' => 'foo',
            'locked' => false
        ]);
        $this->assertTrue($auth->can('index', 'document', $doc));
        $this->assertTrue($auth->can('read', 'document', $doc));
        $this->assertFalse($auth->can('create', 'document', $doc));
        $this->assertFalse($auth->can('update', 'document', $doc));
        $this->assertFalse($auth->can('delete', 'document', $doc));
    }

    public function testCallAuthorizerAsDavid()
    {
        $this->loginAs('david');
        $auth = $this->container->get('kalinka.authorizer');

        $this->assertTrue($auth->can('foo', 'system'));
        $this->assertFalse($auth->can('bar', 'system'));
        $this->assertFalse($auth->can('baz', 'system'));

        $doc = Fixtures\Document::createFromArray([
            'ownerName' => 'david',
            'locked' => false
        ]);
        $this->assertTrue($auth->can('index', 'document', $doc));
        $this->assertTrue($auth->can('read', 'document', $doc));
        $this->assertTrue($auth->can('create', 'document', $doc));
        $this->assertTrue($auth->can('update', 'document', $doc));
        $this->assertTrue($auth->can('delete', 'document', $doc));
    }

    public function testCallAuthorizerAsAnonymousUser()
    {
        $this->loginAs(null);
        $auth = $this->container->get('kalinka.authorizer');

        $this->assertFalse($auth->can('foo', 'system'));
        $this->assertFalse($auth->can('bar', 'system'));
        $this->assertFalse($auth->can('baz', 'system'));

        $doc = Fixtures\Document::createFromArray([
            'ownerName' => 'david',
            'locked' => false
        ]);
        $this->assertFalse($auth->can('index', 'document', $doc));
        $this->assertTrue($auth->can('read', 'document', $doc));
        $this->assertFalse($auth->can('create', 'document', $doc));
        $this->assertFalse($auth->can('update', 'document', $doc));
        $this->assertFalse($auth->can('delete', 'document', $doc));
    }
}
