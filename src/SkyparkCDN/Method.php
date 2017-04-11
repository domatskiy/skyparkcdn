<?php

namespace Domatskiy\SkyparkCDN;

abstract class Method extends Request
{
    function __construct($token)
    {
        $this->token = $token;
    }
}