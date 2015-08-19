<?php

namespace AC\KalinkaBundle;

use AC\Kalinka\Authorizer\RoleAuthorizer;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\HttpFoundation\RequestInterface;

/**
 * This class has a container, and a map of available guard services, so it can load and register
 * guard instances on demand only as needed.
 */
class ContainerAwareRoleAuthorizer extends RoleAuthorizer
{
    private $container;
    private $guardMap = [];
    private $loadedTypes = [];
    private $hasBegunActionPhase = false;

    public function __construct(SecurityContextInterface $context, ContainerInterface $container, $rolePolicies = [], $anonRole = null, $authRole = null)
    {
        $this->container = $container;

        $subject = null;
        $roles = [];

        //check for roles from the security token
        //automatically inject anonymous/authenticated roles, if configured
        if ($token = $context->getToken()) {
            $subject = $context->getToken()->getUser();
            $roles = array_map(function ($item) { return $item->getRole(); }, $token->getRoles());

            if (($token instanceof AnonymousToken) && $anonRole) {
                $roles[] = $anonRole;
            } elseif ($token->isAuthenticated() && $authRole) {
                $roles[] = $authRole;
            }
        } elseif ($anonRole) {
            $roles[] = $anonRole;
        }

        parent::__construct($subject, $roles);

        $this->registerRolePolicies($rolePolicies);
        if (!is_null($anonRole)) {
            $this->registerRolePolicies([$anonRole => []]);
        }
        if (!is_null($authRole)) {
            $this->registerRolePolicies([$authRole => []]);
        }
    }

    public function beginActionPhase(RequestInterface $req)
    {
        if (true === $this->hasBegunActionPhase) {
            throw new \LogicException("Kalinka action phase has already begun.");
        }

        $this->hasBegunActionPhase = true;
    }

    public function hasBegunActionPhase()
    {
        return $this->hasBegunActionPhase;
    }

    public function can($action, $resType, $guardObject = null)
    {
        if (!isset($this->loadedTypes[$resType])) {
            if (isset($this->guardMap[$resType])) {
                $guard = $this->container->get($this->guardMap[$resType]);
                $this->registerGuard($resType, $guard);

                $this->loadedTypes[$resType] = true;
            }
        }

        return parent::can($action, $resType, $guardObject);
    }

    public function registerGuardService($serviceId, $tag)
    {
        $this->guardMap[$tag] = $serviceId;
    }
}
