<?php

namespace AC\KalinkaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AC\KalinkaBundle\HypotheticalRequest;
use AC\KalinkaBundle\Exception\HypotheticalRequestSuccessException;
use AC\KalinkaBundle\Exception\AuthorizationDeniedException;

class HypotheticalCheckController extends Controller
{
    public function checkHypotheticalRoutes(Request $req)
    {
        \dump($req->getContent());
        $data = json_decode($req->getContent(), true);
        if (is_null($data)) {
            throw new HttpException(400, "Could not parse json.");
        }
        \dump($data);
        if (!isset($data['checks']) || !is_array($data['checks'])) {
            throw new HttpException(400, "A 'checks' property containing an array of routes to check is required.");
        }

        $kernel = $this->get('kernel');
        $responseMap = [];
        foreach ($data['checks'] as $check) {
            list($method, $path) = explode(' ', $check);

            if (empty($path) || empty($method)) {
                throw new HttpException(400, sprintf("Each route must be in the format [<method> <path>].  Received: [%]", $check));
            }

            $sub = HypotheticalRequest::create($path, strtoupper($method));

            $caught = false;
            try {
                $kernel->handle($sub, HttpKernelInterface::SUB_REQUEST, true);
            } catch (HypotheticalRequestSuccessException $e) {
                $responseMap[$check] = 200;
                $caught = true;
            } catch (HttpException $e) {
                $responseMap[$check] = $e->getCode();
                $caught = true;
            } // Let other types of exceptions bubble all the way up

            if (!$caught) {
                throw new \LogicException("On $check: Sub request completed without HypotheticalRequestSuccessException");
            }
        }

        return new Response(200, json_encode($responseMap), ['content-type', 'application/json']);
    }
}
