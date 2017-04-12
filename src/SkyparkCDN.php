<?

/**
 * based on
 * https://api-docs.skyparkcdn.com/?lang=ru
 */

namespace Domatskiy;

use Domatskiy\SkyparkCDN\Request;
use Domatskiy\SkyparkCDN\Type\Result;
use Domatskiy\SkyparkCDN\Type\Auth;

class SkyparkCDN extends Request
{


    /**
     * @param $email
     * @param $passw
     * @return Auth
     */
    public function signin($email, $passw)
    {
        /**
         * @var $res Auth
         */
        $res = $this->__request(self::METHOD_POST, '/auth/signin', Auth::class, [
            'email' => $email,
            'password' => $passw
            ]);

        $this->token = $res->token;

        return $res;
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
     * @return Result
     */
    public function signout()
    {
        $result = $this->__request(self::METHOD_GET, '/auth/signout', Result::class);
        $this->token = '';
        return $result;
    }

	
}