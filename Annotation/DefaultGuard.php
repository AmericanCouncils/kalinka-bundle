<?php

namespace AC\KalinkaBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Target;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class DefaultGuard
{
    public $guard;

    public function __construct($guard)
    {
        $this->guard = $guard;
    }
}
