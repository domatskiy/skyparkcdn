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

        if(!isset($this->config['auth']['login']) && !$this->config['auth']['login'])
            throw new \Exception('no config login for auth');

        if(!isset($this->config['auth']['password']) && !$this->config['auth']['password'])
            throw new \Exception('no config password for auth');
        
        var_dump($this->config);

        $this->cdn = new \Domatskiy\SkyparkCDN();
    }
    public function tearDown()
    {
        $this->cdn = NUll;
    }

    public function testSignIN()
    {
        $response = $this->cdn->signin($this->config['auth']['login'], $this->config['auth']['password']);
        $this->assertInstanceOf(SkyparkCDN\RequestResult::class, $response);

        var_dump($response);

        $response = $this->cdn->getBalance();
        $this->assertInstanceOf(SkyparkCDN\RequestResult::class, $response);

        var_dump($response);

        #=========================================================================
        # resource
        #=========================================================================
        if(!isset($this->config['resource']))
            throw new \Exception('no config for resource');

        if(!isset($this->config['resource']['res_1']) && !$this->config['resource']['res_1'])
            throw new \Exception('no config res_1 for resource');

        $response = $this->cdn->purgeAll($this->config['resource']['res_1']);
        $this->assertInstanceOf(SkyparkCDN\RequestResult::class, $response);

        var_dump($response);

        $response = $this->cdn->signout();
        $this->assertInstanceOf(SkyparkCDN\RequestResult::class, $response);

        var_dump($response);

    }

}
