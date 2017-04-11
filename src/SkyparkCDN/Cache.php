<?php

namespace Domatskiy\SkyparkCDN;

class Cache extends Method
{
    /**
     * @param $resource_id
     * @param array $paths
     * @return RequestResult
     */
    public function purge($resource_id, array $paths)
    {
        $result = $this->__request(self::METHOD_POST, '/resources/'.$resource_id.'/purge', [
            'paths' => $paths
            ]);

        return $result;
    }

    /**
     * @param $resource_id
     * @return RequestResult
     * @throws \Exception
     */
    public function purgeAll($resource_id)
    {
        if((int)$resource_id < 1)
            throw new \Exception('nо correct resource_id');

        $result = $this->__request(self::METHOD_POST, '/resources/'.$resource_id.'/purgeAll');

        return $result;
    }

    /**
     * @param $resource_id
     * @param array $paths
     * @return RequestResult
     */
    public function prefetch($resource_id, array $paths)
    {
        $result = $this->__request(self::METHOD_POST, '/resources/'.$resource_id.'/prefetch',[
            'paths' => $paths
            ]);

        return $result;
    }



}