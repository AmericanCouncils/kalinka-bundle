<?php

namespace AC\KalinkaBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class DefaultGuard
{
    protected $guard;

    public function __construct($guard)
    {
        $this->guard = $guard;
    }
}
