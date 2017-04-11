<?

namespace Domatskiy;

class SkyparkCDN
{
    private $api_url = 'https://capi.skyparkcdn.ru/api/v1';
    private $token = '';

    function __construct()
    {

    }

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @return RequestResult
     */
    private function __request($method, $url, array $data = array())
    {
        if($this->session_id)
            $data["SessionId"]= $this->session_id;

        $result = new RequestResult();
        $result_data = array();

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

        $res = $client->request($method, $full_url, [
            'headers' => [
				'Authorization: Bearer: '.$this->token,
				'Content-Type: application/json'
				],
            'timeout' => 60,
            'http_errors' => false,
            'json' => $data
            ]);

        if($res->getStatusCode() == 200)
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
     * @param $login
     * @param $passw
     * @return RequestResult
     */
    public function login($login, $passw)
    {
        $result = $this->__request(self::METHOD_POST, '/auth/signin', [
            'login' => $login,
            'password' => $passw
            ]);

        $data = $result->getData();

        if(!isset($data['token']))
            $result->setError(null, 'auth error');
        else
            $this->token = $data['token'];

        return $result;
    }

    public function logout()
    {
        $result = $this->__request(self::METHOD_POST, '/auth/signout');
        $this->token = '';

        $data = $result->getData();

        if(!isset($data['Success']) || !$data['Success'])
            $result->setError(0, 'logout error');

        return $result;
    }

    public function purgeAll($resource_id)
    {
		if(!is_string($resource_id) || strlen($resource_id) < 1)
            throw new \Exception('но correct resource_id', 'resource_id');
		
        $data = array(
            'Sendings' => []
            );

        $result = $this->__request(self::METHOD_POST, '/resources/'.$resource_id.'/purgeAll');

        return $result;
    }

	
}