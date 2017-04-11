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
        $response = $this->cdn->signin($this->config['auth']['email'], $this->config['auth']['password']);
        $this->assertEquals($response instanceof SkyparkCDN\RequestResult, true);
        $this->assertArrayHasKey('token', $response->getData());

        $data = $response->getData();
        echo 'token: '.$data['token']."\n\n";

        $result = $this->cdn
            ->client()
            ->getBalance();

        print_r($result);

        $this->assertEquals($result instanceof SkyparkCDN\RequestResult, true);
        $this->assertArrayHasKey('currency', $result->getData());
        $this->assertArrayHasKey('value', $result->getData());
        $this->assertArrayHasKey('updatedAt', $result->getData());

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

        $this->assertEquals($result instanceof SkyparkCDN\RequestResult, true);

        var_dump($result);

        $response = $this->cdn->signout();
        $this->assertEquals($result instanceof SkyparkCDN\RequestResult, true);

        #var_dump($result);

    }

}
