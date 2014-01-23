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
    private $guardMap;
    private $roleMap;
    private $user;
    private $loadedTypes = [];

    public function __construct(SecurityContextInterface $context, ContainerInterface $container, $roleMap = array(), $guardMap = array())
    {
        $this->container = $container;
        $this->user = $context->getToken()->getUser();
        $this->roleMap = $roleMap;
        $this->guardMap = $guardMap;
    }

    public function can($action, $resType, $guardObject = null)
    {
        if (!isset($this->loadedTypes[$resType])) {
            if (isset($this->guardMap[$resType])) {
                $data = $this->guardMap[$resType];
                $guard = $this->container->get($data['serviceId']);

                //TODO: this isn't part of the GuardInterface, but it should be
                $this->registerGuard($data[$resType], $guard);
            }
        }

        return parent::can($action, $resType, $guardObject);
    }

    public function registerRoleMap($role, array $map)
    {
        $this->roleMap[$role] = $map;
    }

    public function registerGuardService($serviceId, $tag, array $actions)
    {
        $this->guardMap[$tag] = [
            'serviceId' => $serviceId,
            'actions' => $actions
        ];
    }
}
