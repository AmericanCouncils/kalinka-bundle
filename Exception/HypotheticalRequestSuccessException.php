<?php

namespace AC\KalinkaBundle\Exception;

class HypotheticalRequestSuccessException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Hypothetical request reached the action phase.");
    }
}
