<?php

namespace AC\KalinkaBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Serialize
{
    public $action;
    public $guard;

    public function __construct($action, $guard = null)
    {
        $this->guard = $guard;
        $this->action = $action;
    }
}
