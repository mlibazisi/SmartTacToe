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

use Services\ContainerService;
use Services\OAuthService;

class OAuthServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testGetAccessToken()
    {

        $expected       = [
            'access_token'  => 'test_token'
        ];
        $json_encoded   = json_encode( $expected );
        $access_url     = 'http://example.org';
        $headers        = [
            'Accept' => 'application/json'
        ];

        $http_client = $this->getMock( 'Services\HttpClientService' );
        $http_client->expects( $this->once() )
            ->method('get')
            ->with(
                $this->equalTo( $access_url ),
                $this->equalTo( $headers )
            )
            ->will(
                $this->returnValue( $json_encoded )
            );

        $container  = $this->getMock( 'Services\ContainerService' );
        $container->expects( $this->once() )
            ->method('get')
            ->with( 'http_client' )
            ->will(
                $this->returnValue( $http_client )
            );

        $o_auth = new OAuthService( $container );
        $result = $o_auth->getAccessToken( $access_url );

        $this->assertEquals( $expected['access_token'], $result );

    }

}