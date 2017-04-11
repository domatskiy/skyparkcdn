<?php

namespace Domatskiy\SkyparkCDN;

class RequestResult
{
    private $errors = array();
    private $data = array();
    private $url = array();

    function __construct()
    {

    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
    /**
     * @param $code
     * @param string $message
     */
    public function setError($code, $message = '')
    {
        if($code)
            $this->errors[$code] = $message;
        else
            $this->errors[$code] = $message;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    
}