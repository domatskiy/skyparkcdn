<?php

namespace Domatskiy\SkyparkCDN;

use Domatskiy\SkyparkCDN\Exception\ForbiddenException;
use Domatskiy\SkyparkCDN\Exception\NotFoundException;
use Domatskiy\SkyparkCDN\Exception\ObjectTypeException;
use Domatskiy\SkyparkCDN\Type\Result;
use Domatskiy\SkyparkCDN\Type\Type;

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
     * @param $object
     * @param array $data
     * @return mixed
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    protected function __request($method, $url, $object, array $data = array())
    {
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

        $full_url = $this->api_url.$url;

        if($method == self::METHOD_GET)
        {
            $d = array();

            foreach($data as $key => $val)
                $d[] = $key.'='.urlencode($val);

            $full_url .= $d ? '?'.http_build_query($d) : '';
        }
        else
        {
            $params['json'] = $data;
        }

        #в запросе указывать Content type равным “application/json”,
        #​ таймаут ожидания выполнения запроса 60 секунд,

        $options = [
            #'form_params' => $data,
            #'body' => $data,
        ];

        $client = new \GuzzleHttp\Client();

        $res = $client->request($method, $full_url, $params);

        if((int)$res->getStatusCode() >= 200 && (int)$res->getStatusCode() <= 226)
        {
            $content_type = $res->getHeader('Content-Type');

            if(is_array($content_type))
                $content_type = current($content_type);

            if(strpos($content_type, 'application/json') !== false)
            {
                try{
                    $result_data = json_decode($res->getBody(), false);

                    try{
                        return new $object($result_data);
                    }
                    catch (\Exception $e){
                        throw new ObjectTypeException($e->getMessage());
                    }

                }
                catch (\Exception $e)
                {

                    throw new Exception\JsonException('Exception json_decode');
                }

            }
            else
            {
                return new Result(null);
            }

        }
        elseif($res->getStatusCode() == 403)
            throw new ForbiddenException('Access forbidden for '.$url);
        elseif($res->getStatusCode() == 404)
            throw new NotFoundException('Not found path '.$url);
        else
            throw new NotFoundException($res->getBody().' status '.$res->getStatusCode());
    }
}