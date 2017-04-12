<?php

namespace Domatskiy\SkyparkCDN;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    private $api_url = 'https://capi.skyparkcdn.ru/api/v1';

    protected $token = '';

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @return RequestResult
     */
    protected function __request($method, $url, array $data = array())
    {
        $result = new RequestResult();
        $result_data = [];

        $d = array();

        foreach($data as $key => $val)
            $d[] = $key.'='.urlencode($val);

        $full_url = $this->api_url.$url;

        if($method == self::METHOD_GET)
            $full_url .= $d ? '?'.http_build_query($d) : '';

        $result->setUrl($full_url);

        #в запросе указывать Content type равным “application/json”,
        #​ таймаут ожидания выполнения запроса 60 секунд,

        $options = [
            #'form_params' => $data,
            #'body' => $data,
        ];

        $client = new \GuzzleHttp\Client();

        $headers = [
            'Content-Type' => 'application/json'
        ];

        if($this->token)
            $headers['Authorization'] = 'Bearer '.$this->token;

        $params = [
            'headers' => $headers,
            'timeout' => 60,
            'http_errors' => false,
        ];

        if($method == self::METHOD_POST)
            $params['json'] = $data;

        $res = $client->request($method, $full_url, $params);

        if((int)$res->getStatusCode() >= 200 && (int)$res->getStatusCode() <= 226)
        {
            try{
                $result_data = json_decode($res->getBody(), true);

                if(!is_array($result_data))
                    $result_data = array();

            }
            catch (\Exception $e)
            {
                new Exception\JsonException();
            }

        }
        else
        {
            $result->setError($res->getStatusCode(), 'Err: '.$res->getBody());
        }

        $result->setData($result_data);

        return $result;
    }
}