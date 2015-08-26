<?php

namespace AC\KalinkaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use AC\KalinkaBundle\ContainerAwareRoleAuthorizer;
use AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity as Fixtures;

class HypotheticalCheckControllerTest extends WebTestCase
{
    private function call($data)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/check-hypothetical',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        return $client->getResponse();
    }

    public function testCheckGoodHypotheticableRoutes()
    {
        $response = $this->call([
            'checks' => [
                'POST /hypotheticable/good-success',
                'POST /hypotheticable/good-fail'
            ]
        ]);

        $data = json_decode($response->getContent(), true);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(200, $data['checks']['POST /hypotheticable/good-success']);
        $this->assertSame(403, $data['checks']['POST /hypotheticable/good-fail']);
    }

    public function testCheckBadHypotheticableRoute()
    {
        $this->setExpectedException('LogicException');
        $response = $this->call([
            'checks' => [
                'POST /hypotheticable/bad',
            ]
        ]);
    }

    public function testCheckNonHypotheticableRoute()
    {
        $this->setExpectedException('LogicException');
        $response = $this->call([
            'checks' => [
                'POST /hypotheticable/but-not-really',
            ]
        ]);
    }
}
