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

        $response = new ResponseService();
        $response->setHeaders( $custom_headers );

        $result = $response->getHeaders();
        $this->assertEquals( $expected_headers, $result );

    }

    /**
     * @test
     */
    public function testSetContent()
    {

        $content    = 'Hello World!';
        $response   = new ResponseService();
        $response->setContent( $content );

        $result = $response->getContent();
        $this->assertEquals( $content, $result );

    }

    /**
     * @test
     */
    public function testSetStatusCode()
    {

        $code       = 300;
        $response   = new ResponseService();
        $response->setStatusCode( $code );

        $result = $response->getStatusCode();
        $this->assertEquals( $code, $result );

    }

}