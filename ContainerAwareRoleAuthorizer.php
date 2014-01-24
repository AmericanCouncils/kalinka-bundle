<?php

namespace AC\KalinkaBundle;

use AC\Kalinka\Authorizer\RoleAuthorizer;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class has a container, and a map of available guard services, so it can load and register
 * guard instances on demand only as needed.
 */
class ContainerAwareRoleAuthorizer extends RoleAuthorizer
{
    private $container;
    private $guardMap = [];
    private $loadedTypes = [];

    public function __construct(SecurityContextInterface $context, ContainerInterface $container, $rolePolicies = [], $guardMap = [])
    {
        $this->container = $container;
        $this->guardMap = $guardMap;

        $subject = null;
        $roles = [];

        if ($context->getToken()) {
            $subject = $context->getToken()->getUser();
            $roles = $subject->getRoles();
        }

        parent::__construct($subject, $roles);

        $this->registerRolePolicies($rolePolicies);
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
