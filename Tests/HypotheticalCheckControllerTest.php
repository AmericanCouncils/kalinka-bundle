<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use AC\KalinkaBundle\ContainerAwareRoleAuthorizer;
use AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity as Fixtures;

class HypotheticalCheckControllerTest extends WebTestCase
{
    private function call($json)
    {
        return static::createClient()->request(
            'POST',
            '/check-hypothetical',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $json
        );
    }

    public function testCheckGoodHypotheticableRoutes()
    {
        $response = $this->call("{'checks':['POST /hypotheticable/good-success','POST /hypotheticable/good-fail']}");

        $res = json_decode($response->getContent(), true);
        $this->assertSame(200, $res['checks']['POST /hypotheticable/good-success']);
        $this->assertSame(403, $res['checks']['POST /hypotheticable/good-fail']);
    }

    public function testCheckBadHypotheticableRoute()
    {

    }

    public function testCheckNonHypotheticableRoute()
    {

    }
}
