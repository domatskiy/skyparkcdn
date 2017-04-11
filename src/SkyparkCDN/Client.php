<?php

namespace Domatskiy\SkyparkCDN;

class Client extends Method
{
    /**
     * @return RequestResult
     */
    public function get()
    {
        $result = $this->__request(self::METHOD_GET, '/clients/me/');

        return $result;
    }

    /**
     * @return RequestResult
     */
    public function getBalance()
    {
        $result = $this->__request(self::METHOD_GET, '/clients/balance');

        return $result;
    }
}