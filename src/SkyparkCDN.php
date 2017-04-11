<?

/**
 * based on
 * https://api-docs.skyparkcdn.com/?lang=ru
 */

namespace Domatskiy;

class SkyparkCDN
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    private $api_url = 'https://capi.skyparkcdn.ru/api/v1';
    private $token = '';

    function __construct()
    {

    }

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
     * @return SkyparkCDN\RequestResult
     */
    private function __request($method, $url, array $data = array())
    {
        $result = new SkyparkCDN\RequestResult();
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
                $result_data = (array)json_decode($res->getBody(), true);

                if(!is_array($result_data))
                    $result_data = array();

            }
            catch (\Exception $e)
            {
                echo 'Exception: '.$e->getMessage();
                $result->setError($e->getCode(), $e->getMessage());
            }

        }
        else
        {
            $result->setError($res->getStatusCode(), 'Err: '.$res->getBody());
        }

        $result->setData($result_data);

        return $result;
    }

    /**
     * @param $email
     * @param $passw
     * @return SkyparkCDN\RequestResult
     */
    public function signin($email, $passw)
    {
        $result = $this->__request(self::METHOD_POST, '/auth/signin', [
            'email' => $email,
            'password' => $passw
            ]);

        $data = $result->getData();

        if(!isset($data['token']) || strlen($data['token']) < 150)
            $result->setError(null, 'auth error');
        else
            $this->token = $data['token'];

        return $result;
    }

    /**
     * @return SkyparkCDN\RequestResult
     */
    public function getBalance()
    {
        $result = $this->__request(self::METHOD_GET, '/clients/balance');

        return $result;
    }

    /**
     * @param $resource_id
     * @return SkyparkCDN\RequestResult
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
     * @return SkyparkCDN\RequestResult
     */
    public function signout()
    {
        $result = $this->__request(self::METHOD_GET, '/auth/signout');
        $this->token = '';
        return $result;
    }

	
}