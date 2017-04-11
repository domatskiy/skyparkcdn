<?php

namespace Domatskiy\SkyparkCDN;

class Users extends Method
{
    /**
     *
     * @param $resource_id
     * @return RequestResult
     * @throws \Exception
     */
    public function getList()
    {
        $result = $this->__request(self::METHOD_GET, '/users');

        return $result;
    }

    public function add(array $data)
    {
        $fields = ['email', 'name', 'phone'];

        #TODO check fields

        $result = $this->__request(self::METHOD_POST, '/users', $data);

        return $result;
    }

    public function get($id)
    {
        $result = $this->__request(self::METHOD_GET, '/users/'.(int)$id);
        return $result;
    }

    public function delete($id)
    {
        $result = $this->__request(self::METHOD_DELETE, '/users/'.(int)$id);
        return $result;
    }

    public function setPassword($id, array $password)
    {
        $fields = ['old', 'new', 'double'];
        $result = $this->__request(self::METHOD_PUT, '/users/'.(int)$id.'/password');
        return $result;
    }
}