<?php

namespace AC\KalinkaBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class DefaultGuard
{
    public $guard;

    public function __construct($guard)
    {
        $this->guard = $guard;
    }
}
