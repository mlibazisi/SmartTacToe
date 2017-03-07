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
use Services\ResponseService;

class ResponseServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testSetHeaders()
    {

        $custom_headers     = [
            'Cache-name'    => 'cache-value',
        ];
        $expected_headers   = [
            'Cache-name'    => 'cache-value',
            'Content-Type'  => 'text/html; charset=UTF-8'
        ];

        $container          = $this->getMock( 'Services\ContainerService' );
        $response_service  = new ResponseService( $container );
        $response_service->setHeaders( $custom_headers );

        $result = $response_service->getHeaders();
        $this->assertEquals( $expected_headers, $result );

    }

    /**
     * @test
     */
    public function testSetContent()
    {

        $container          = $this->getMock( 'Services\ContainerService' );
        $content            = 'Hello World!';
        $response_service   = new ResponseService( $container );
        $response_service->setContent( $content );

        $result = $response_service->getContent();
        $this->assertEquals( $content, $result );

    }

    /**
     * @test
     */
    public function testSetStatusCode()
    {

        $code               = 300;
        $container          = $this->getMock( 'Services\ContainerService' );
        $response_service   = new ResponseService( $container );
        $response_service->setStatusCode( $code );

        $result = $response_service->getStatusCode();
        $this->assertEquals( $code, $result );

    }

    /**
     * @test
     */
    public function testSendHeadersAlreadySent()
    {

        $functions_helper = $this->getMock( 'Helpers\FunctionHelper' );
        $functions_helper->expects( $this->once() )
            ->method('headersSent')
            ->will(
                $this->returnValue( TRUE )
            );

        $functions_helper->expects( $this->once() )
            ->method('functionExists')
            ->with(
                $this->equalTo( 'fastcgi_finish_request' )
            )
            ->will(
                $this->returnValue( TRUE )
            );

        $functions_helper->expects( $this->once() )
            ->method('fastcgiFinishRequest');

        $container  = $this->getMock( 'Services\ContainerService' );
        $container->expects( $this->once() )
            ->method( 'get' )
            ->with(
                $this->equalTo( HelperConstants::HELPER_FUNCTIONS )
            )
            ->will(
                $this->returnValue( $functions_helper )
            );


        $response_service = new ResponseService( $container );
        $response_service->send();

    }

    /**
     * @test
     */
    public function testSendHeadersNotAlreadySent()
    {

        $functions_helper = $this->getMock( 'Helpers\FunctionHelper' );
        $functions_helper->expects( $this->once() )
            ->method('headersSent')
            ->will(
                $this->returnValue( FALSE )
            );

        $functions_helper->expects( $this->once() )
            ->method('functionExists')
            ->with(
                $this->equalTo( 'fastcgi_finish_request' )
            )
            ->will(
                $this->returnValue( FALSE )
            );

        $functions_helper->expects( $this->once() )
            ->method('header')
            ->with(
                $this->equalTo( 'Content-Type: text/html; charset=UTF-8' ),
                $this->equalTo( FALSE ),
                $this->equalTo( 200 )
            );

        $container  = $this->getMock( 'Services\ContainerService' );
        $container->expects( $this->once() )
            ->method( 'get' )
            ->with(
                $this->equalTo( HelperConstants::HELPER_FUNCTIONS )
            )
            ->will(
                $this->returnValue( $functions_helper )
            );

        $response_service = new ResponseService( $container );
        $response_service->send();

    }


}