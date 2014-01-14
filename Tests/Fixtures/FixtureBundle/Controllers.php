<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\HttpException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

/**
 * These controller routes are called by various tests.
 **/
class Controllers extends Controller
{
    /**
     * @Route("/hello-world")
     **/
    public function helloWorldAction(Request $req)
    {
        return new Response('Hello world!');
    }

}
