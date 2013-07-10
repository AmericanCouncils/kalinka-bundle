<?php

namespace AC\KalinkaBundle;

use Symfony\Component\Security\Core\User\UserInterface;

interface SemiAuthorizedUserInterface extends UserInterface
{
    public function getRoleInclusions();
    public function getRoleExclusions();
}
