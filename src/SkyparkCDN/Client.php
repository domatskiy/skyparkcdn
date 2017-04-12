<?php

namespace Domatskiy\SkyparkCDN;

use Domatskiy\SkyparkCDN\Type\Balance;

class Client extends Method
{
    /**
     * @return Type\Client
     */
    public function getClient()
    {
        return $this->__request(self::METHOD_GET, '/clients/me/', Client::class);
    }

    /**
     * @return Type\Balance
     */
    public function getBalance()
    {
        return $this->__request(self::METHOD_GET, '/clients/balance/', Balance::class);
    }
}