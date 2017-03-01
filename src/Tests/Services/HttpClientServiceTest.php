<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Tests\Services;
use Services\HttpClientService;

require_once __DIR__ . '/../ServiceBaseTestCase.php';

class HttpClientServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testSetOption()
    {

        $name       = 'cURL-Opt-Name';
        $value      = 'value';
        $url        = 'example.org';
        $expected   = [
            new \stdClass(),
            $name,
            $value
        ];

        $http_client = new HttpClientService();
        $http_client->init( $url );
        $result = $http_client->setOption( $name, $value );

        $this->assertEquals( $expected, $result );

    }

    /**
     * @test
     */
    public function testJsonPost()
    {

        $url            = 'example.org';
        $http_client    = new HttpClientService();
        $result         = $http_client->jsonPost( $url, [ 'test' ] );

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testGet()
    {

        $url            = 'example.org';
        $http_client    = new HttpClientService();
        $result         = $http_client->get( $url );

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testPost()
    {

        $url            = 'example.org';
        $http_client    = new HttpClientService();
        $result         = $http_client->post( $url, [ 'test' ] );

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testExecute()
    {

        $url            = 'example.org';
        $http_client    = new HttpClientService();
        $http_client->init( $url );
        $result         = $http_client->execute();

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testInit()
    {

        $url            = 'example.org';
        $expected       = new \stdClass();
        $http_client    = new HttpClientService();
        $http_client->init( $url );
        $result         = $http_client->getHandle();

        $this->assertEquals( $expected, $result );

    }

}