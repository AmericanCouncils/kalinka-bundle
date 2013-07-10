<?php

namespace AC\KalinkaBundle;

use Symfony\Component\Security\Core\User\UserInterface;
use AC\Kalinka\Authorizer\RoleAuthorizer;

class AuthorizerFactory
{
    public function buildRoleAuthorizer(UserInterface $user)
    {
       return new RoleAuthorizer($user, $user->getRoles()); 
    }
}
