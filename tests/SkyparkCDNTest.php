<?php
namespace Domatskiy\Tests;

use Domatskiy\SkyparkCDN;

class SkyparkCDNTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Domatskiy\SkyparkCDN
     */
    private $cdn;
    private $config;

    public function setUp()
    {
        $reader = new \Piwik\Ini\IniReader();
        $this->config = $reader->readFile(__DIR__.'/config.ini');

        if(!isset($this->config['auth']))
            throw new \Exception('no config for auth');

        if(!isset($this->config['auth']['email']) && !$this->config['auth']['email'])
            throw new \Exception('no config email for auth');

        if(!isset($this->config['auth']['password']) && !$this->config['auth']['password'])
            throw new \Exception('no config password for auth');
        
        echo "\n";
        echo "config: \n";
        echo 'email: '.$this->config['auth']['email']."\n";
        echo 'passw: '.$this->config['auth']['password']."\n\n";

        $this->cdn = new \Domatskiy\SkyparkCDN();
    }
    public function tearDown()
    {
        $this->cdn = NUll;
    }

    public function testAPI()
    {
        try{

            $user = $this->cdn->signin($this->config['auth']['email'], $this->config['auth']['password']);
            echo 'token: '.$user->token."\n\n";

            $balance = $this->cdn
                ->client()
                ->getBalance();

            $this->assertEquals($balance instanceof SkyparkCDN\Type\Balance, true);

            print_r($balance);

            #=========================================================================
            # resource
            #=========================================================================
            if(!isset($this->config['resource']))
                throw new \Exception('no config for resource');

            if(!isset($this->config['resource']['res_1']) && !$this->config['resource']['res_1'])
                throw new \Exception('no config res_1 for resource');

            $result = $this->cdn
                ->cache()
                ->purgeAll($this->config['resource']['res_1']);

            var_dump($result);

            $rsOut = $this->cdn->signout();


            #var_dump($result);

        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            $this->assertEquals(false, true);
        }


    }

}
