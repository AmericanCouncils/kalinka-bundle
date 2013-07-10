<?php

namespace AC\KalinkaBundle;

class AuthorizerContainer
{
    private $config;
    private $authFactory;
    private $securityContext;

    private $guardMap = [];
    private $actionMap = [];
    private $rolePolicies = [];

    private $authorizerCache = [];

    public function __construct($config, $authFactory, $securityContext, $container)
    {
        $this->config = $config;
        $this->authFactory = $authFactory;
        $this->securityContext = $securityContext;

        foreach ($config['objects'] as $name => $objConf) {
            $this->guardMap[$docName] = function() {
                return $container->get($objConf['guard']);
            };
            $this->actionMap[$docName] = $objConf['actions'];
        }
    }

    public function authorizer(SemiAuthorizedUserInterface $user = null)
    {
        if (is_null($user)) {
            $user = $this->securityContext->getToken()->getUser();
        }

        $username = $user->getUsername();
        if (array_key_exists($username, $this->authorizerCache)) {
            return $authorizerCache[$username];
        }

        $a = $this->authFactory->buildRoleAuthorizer($user);
        $a->registerGuards($this->guardMap);
        $a->registerActions($this->actionMap);
        $a->registerRolePolicies($this->config['roles']);
        $a->registerRoleInclusions($user->getRoleInclusions());
        $a->registerRoleExclusions($user->getRoleExclusions());

        $this->authorizerCache[$username] = $a;
        return a;
    }
}
