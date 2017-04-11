<?php

namespace Domatskiy\Tests;

use Domatskiy\SkyparkCDN\RequestResult;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testResponseErrors()
    {
        $result = new RequestResult();
        $this->assertEquals($result instanceof RequestResult, true);

        $result->setError('test');
        $this->assertFalse($result->isSuccess());

    }

}
