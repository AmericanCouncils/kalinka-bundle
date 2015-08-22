<?php

namespace AC\KalinkaBundle\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use AC\KalinkaBundle\ContainerAwareRoleAuthorizer;
use AC\KalinkaBundle\HypotheticalRequest;

/**
 * Checks the request/response to ensure that HypotheticalRequest instances
 * were handled properly.
 */
class HypotheticalRequestSubscriber implements EventSubscriberInterface
{
    private $kalinka;

    public function __construct(ContainerAwareRoleAuthorizer $kalinka)
    {
        $this->kalinka = $kalinka;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => [
                //listen for the request fairly late to allow routing and other
                //potential app-level items to have their turn configuring the request
                ['onKernelRequest', -128]
            ],
            'kernel.response' => [
                //listen super early - no point doing anything else if the request
                //was handled improperly to begin with
                ['onKernelResponse', 255]
            ]
        ];
    }

    public function onKernelRequest(GetResponseEvent $e)
    {
        $req = $e->getRequest();

        if ($req instanceof HypotheticalRequest) {
            if ($e->isMasterRequest()) {
                throw new \InvalidArgumentException('Hypothetical requests may only be used as sub requests.');
            }

            if (!$req->attributes->get('_kalinka_hypotheticable', false)) {
                throw new \LogicException("Cannot run a hypothetical request on a non-hypotheticable route.");
            }
        }
    }

    public function onKernelResponse(FilterResponseEvent $e)
    {
        $req = $e->getRequest();

        if ($req instanceof HypotheticalRequest && !$kalinka->hasBegunActionPhase()) {
            throw new \LogicException("No action phase was initiated during a hypothetical request.");
        }
    }
}
