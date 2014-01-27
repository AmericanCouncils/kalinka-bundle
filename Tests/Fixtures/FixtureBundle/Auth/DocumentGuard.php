<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Auth;

use AC\Kalinka\Guard\BaseGuard;

class DocumentGuard extends BaseGuard
{
    public function getActions()
    {
        return ['index','create','read','update','delete'];
    }

    public function policyOwner($subj, $obj = null) { return $subj->getUsername() == $obj->ownerName; }

    public function policyUnlocked($subj, $obj) { return !$obj->getLocked(); }

    public function policyLanguage($subj, $obj) { return in_array($obj->language, $subj->getLanguages()); }
}
