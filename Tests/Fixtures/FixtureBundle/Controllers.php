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
use AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity\Document;
use AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity\Robot;

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

    /**
     * @Route("/document")
     **/
    public function documentAction(Request $req)
    {
        $doc = new Document();
        $doc->setOwnerName("Charles Barkley");
        $doc->setComments("Sir Charles is revealed to be a smart, thoughtful and authentic gentleman.");
        $doc->setLanguage("English");
        $doc->setTitle("Sir Charles: The Wit and Wisdom of Charles Barkley");
        $doc->setContent("Known for making news on and off the court, Barkley goes one-one-one with himself in his autobiography, Sir Charles.");
        $doc->setDateCreated(0);
        $doc->setDateModified(1);
        $serializer = $this->get('jms_serializer');
        $serialized = $serializer->serialize($doc, 'json');

        return new Response($serialized);
    }

    /**
     * @Route("/robot")
     * @Method("GET")
     **/
    public function getRobotAction(Request $req)
    {
        $robot = new Robot();
        $robot->setName("Arnold");
        $robot->setMake("Cyberdyne");
        $robot->setModel("T-850");
        $robot->setOperationalStatus('INACTIVE');
        $robot->setFriendlyToHumans(false);
        $serializer = $this->get('jms_serializer');
        $serialized = $serializer->serialize($doc, 'json');

        return new Response($serialized);
    }

    /**
     * @Route("/robot")
     * @Method("POST")
     **/
    public function createRobotAction(Request $req)
    {
        $auth = $this->get('kalinka.authorizer');
        $serializer = $this->get('jms_serializer');
        // $guard = $this->get('app.guard.robot');

        if (!$auth->can('create', 'robot')) {
            throw new HttpException(401);
        }

        $params = json_decode($req->getContent(), true);
        $robot = $serializer->deserialize(
            $req->getContent(),
            'AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity\Robot',
            'json'
        );

        return new Response($serializer->serialize($robot, 'json'));

    }

    /**
     * @Route("/robot/activate")
     * @Method("POST")
     **/
    public function activateRobotAction(Request $req)
    {
        $auth = $this->get('kalinka.authorizer');
        $serializer = $this->get('jms_serializer');
        if (!$auth->can('activate', 'robot')) {
            throw new HttpException(401);
        }
        $robot = new Robot();
        $robot->setName("Arnold");
        $robot->setMake("Cyberdyne");
        $robot->setModel("T-850");
        $robot->setOperationalStatus('ACTIVE');
        $robot->setFriendlyToHumans(false);
        $serialized = $serializer->serialize($robot, 'json');

        return new Response($serialized);

    }

    /**
     * @Route("/robot/befriend")
     * @Method("POST")
     **/
    public function befriendRobotAction(Request $req)
    {
        $auth = $this->get('kalinka.authorizer');
        $serializer = $this->get('jms_serializer');
        if (!$auth->can('befriend', 'robot')) {
            throw new HttpException(401);
        }
        $robot = new Robot();
        $robot->setName("Arnold");
        $robot->setMake("Cyberdyne");
        $robot->setModel("T-850");
        $robot->setOperationalStatus('ACTIVE');
        $robot->setFriendlyToHumans(true);
        $serialized = $serializer->serialize($robot, 'json');

        return new Response($serialized);

    }
}
