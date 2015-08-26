<?php

namespace AC\KalinkaBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorizationDeniedException extends HttpException {

    public function __construct($msg = "Authorization was denied.")
    {
        parent::__construct(403, $msg);
    }
}
