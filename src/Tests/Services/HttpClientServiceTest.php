<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Tests\Services;
use Constants\HelperConstants;
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

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $http_client = new HttpClientService( $container );
        $http_client->init( $url );
        $result = $http_client->setOption( $name, $value );

        $this->assertEquals( $expected, $result );

    }

    /**
     * @test
     */
    public function testJsonPost()
    {

        $data       = [ 'test' => 'data' ];
        $encoded    = '{test:data}';
        $headers    = [
            'Content-Type'      => 'application/json',
            'Content-Length'    => 11
        ];

        $functions_helper = $this->getMockBuilder( 'Helpers\FunctionHelper' )
            ->getMock();

        $functions_helper->expects( $this->at( 0 ) )
            ->method('jsonEncode')
            ->with(
                $this->equalTo( $data )
            )
            ->will(
                $this->returnValue( $encoded )
            );

        $functions_helper->expects( $this->at( 1 ) )
            ->method('arrayMerge')
            ->with(
                $this->equalTo( [] ),
                $this->equalTo( $headers )
            )
            ->will(
                $this->returnValue( $headers )
            );

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $container->expects( $this->once() )
            ->method('get')
            ->with( HelperConstants::HELPER_FUNCTIONS  )
            ->will(
                $this->returnValue( $functions_helper )
            );

        $url            = 'example.org';
        $http_client    = new HttpClientService( $container );
        $http_client->jsonPost( $url, $data );

    }

    /**
     * @test
     */
    public function testGet()
    {

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $url            = 'example.org';
        $http_client    = new HttpClientService( $container );
        $result         = $http_client->get( $url );

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testPost()
    {

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $url            = 'example.org';
        $http_client    = new HttpClientService( $container );
        $result         = $http_client->post( $url, [ 'test' ] );

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testExecute()
    {

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $url            = 'example.org';
        $http_client    = new HttpClientService( $container );
        $http_client->init( $url );
        $result         = $http_client->execute();

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testInit()
    {

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $url            = 'example.org';
        $expected       = new \stdClass();
        $http_client    = new HttpClientService( $container );
        $http_client->init( $url );
        $result         = $http_client->getHandle();

        $this->assertEquals( $expected, $result );

    }

}