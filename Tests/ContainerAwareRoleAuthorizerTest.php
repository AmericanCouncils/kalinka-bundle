<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use AC\KalinkaBundle\ContainerAwareRoleAuthorizer;
use AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Model as Fixtures;

class ContainerAwareRoleAuthorizerTest extends WebTestCase
{
    protected $container;

    /**
     * Setup puts the container in a state as if it were handling an active request.
     *
     * This is needed to force the `security.context` service to actually be able to derive a user.
     */
    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $this->container = $kernel->getContainer();

        //force container into request scope by faking the request and session as test user
        $container->enterScope('request');
        $request = Request::create('GET', '/hello-world', [], [], [], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'test'
        ]);
        $session = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $request->setSession($session);
        $container->set('request', $request);
    }

    public function tearDown()
    {
        $this->container->leaveScope('request');
    }

    public function testGetAuthorizerService()
    {
        $authorizer = $this->container->get('kalinka.authorizer');

        $this->assertTrue($authorizer instanceof ContainerAwareRoleAuthorizer);
    }

    public function testCallAuthorizerWithAdminUser()
    {
        $auth = $this->container->get('kalinka.authorizer');

        $this->assertTrue($auth->can('foo', 'system'));
        $this->assertTrue($auth->can('bar', 'system'));
        $this->assertTrue($auth->can('baz', 'system'));


        $doc = Fixtures\Document::createFromArray([
            'ownerId' => 1,
            'title' => 'Foo',
            'content' => "Barzinbaz"
        ]);

        $this->assertTrue($auth->can('index', 'document', $doc));
    }

    public function testCallAuthorizerWithStudentUser()
    {
        $this->markTestSkipped();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $user->setRoles(['teacher','student']);

        $this->assertTrue($auth->can('foo', 'system'));
        $this->assertTrue($auth->can('bar', 'system'));
        $this->assertTrue($auth->can('baz', 'system'));
    }

    public function testCallAuthorizerWithTeacherUser()
    {
        $this->markTestSkipped();
    }
}
