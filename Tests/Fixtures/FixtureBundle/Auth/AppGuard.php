<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Auth;

use Kalinka\GuardInterface;
use Kalinka\Guard\BaseGuard;

class AppGuard extends BaseGuard
{

    public function policyFoo($subj, $obj = null) { return false; }

    public function policyBar($subj, $obj) { return true; }

    public function policyBaz($subj, $obj) { return true; }
}
