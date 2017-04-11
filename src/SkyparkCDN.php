<?

/**
 * based on
 * https://api-docs.skyparkcdn.com/?lang=ru
 */

namespace Domatskiy;

use Domatskiy\SkyparkCDN\Request;

class SkyparkCDN extends Request
{


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

    public function users()
    {
        return new SkyparkCDN\Users($this->token);
    }

    public function client()
    {
        return new SkyparkCDN\Client($this->token);
    }

    public function cache()
    {
        return new SkyparkCDN\Cache($this->token);
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