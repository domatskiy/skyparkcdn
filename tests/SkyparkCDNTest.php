<?php
namespace Domatskiy\Tests;

//use Domatskiy\SkyparkCDN\Response;

class SkyparkCDNTest extends \PHPUnit_Framework_TestCase
{
    private $cdn;

    public function setUp()
    {
        $reader = new \Piwik\Ini\IniReader();
        $config = $reader->readFile(__DIR__.'/config.ini');

        if(!isset($config['auth']))
            throw new \Exception('no config for auth');

        if(!isset($config['auth']['login']) && !$config['auth']['login'])
            throw new \Exception('no config login for auth');

        if(!isset($config['auth']['password']) && !$config['auth']['password'])
            throw new \Exception('no config password for auth');
        
        var_dump($config);

        $this->cdn = new \Domatskiy\SkyparkCDN();
    }
    public function tearDown()
    {
        $this->cdn = NUll;
    }

    public function testAuth()
    {
        $response = $this->cdn->signin($config['auth']['login'], $config['auth']['password']);
        $this->assertInstanceOf(Response::class, $response);
    }

}
