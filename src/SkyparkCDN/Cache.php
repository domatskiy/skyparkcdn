<?php

namespace Domatskiy\SkyparkCDN;

use Domatskiy\SkyparkCDN\Type\Result;

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
     * @return Result
     * @throws \Exception
     */
    public function purgeAll($resource_id)
    {
        if((int)$resource_id < 1)
            throw new \Exception('nо correct resource_id');

        return $this->__request(self::METHOD_POST, '/resources/'.$resource_id.'/purgeAll', Result::class);
    }

    /**
     * @param $resource_id
     * @param array $paths
     * @return Result
     */
    public function prefetch($resource_id, array $paths)
    {
        return $this->__request(self::METHOD_POST, '/resources/'.$resource_id.'/prefetch', Result::class, [
            'paths' => $paths
            ]);
    }



}