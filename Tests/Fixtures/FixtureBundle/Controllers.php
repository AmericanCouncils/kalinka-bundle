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
        $doc->setComments("Man! How did Charles Barkley get in here?");
        $doc->setLanguage("English");
        $doc->setTitle("Sir Charles: The Wit and Wisdom of Charles Barkley");
        $doc->setContent("Known for making news on and off the court, Barkley goes one-one-one with himself in his autobiography, Sir Charles.");
        $doc->setDateCreated(0);
        $doc->setDateModified(1);

        // print_r($this->get('annotation_reader'));
        $serializer = $this->get('jms_serializer');
        $serializer->serialize($doc, 'json');
        // $data = $serializer->deserialize($inputStr, $typeName, $format);


        // serialize it!

        return new Response('got to the end!');
    }
}
