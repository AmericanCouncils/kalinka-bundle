<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Auth;

use AC\Kalinka\Guard\BaseGuard;

class AppGuard extends BaseGuard
{
    public function getActions()
    {
        return ['foo','bar','baz'];
    }

    public function policyFoo($subj, $obj = null) { return true; }

    public function policyBar($subj, $obj = null) { return true; }

    public function policyBaz($subj, $obj = null) { return false; }
}
