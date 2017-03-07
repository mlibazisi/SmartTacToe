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
use Constants\ServiceConstants;
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

        $json_encoded   = '{access_token:test_token}';
        $access_url     = 'http://example.org';
        $headers        = [
            'Accept' => 'application/json'
        ];

        $functions_helper = $this->getMock( 'Helpers\FunctionHelper' );
        $functions_helper->expects( $this->once() )
            ->method('jsonDecode')
            ->with(
                $this->equalTo( $json_encoded )
            )
            ->will(
                $this->returnValue( $expected )
            );

        $http_client = $this->getMockBuilder( 'Services\HttpClientService' )
        ->disableOriginalConstructor()
        ->getMock();

        $http_client->expects( $this->once() )
            ->method('get')
            ->with(
                $this->equalTo( $access_url ),
                $this->equalTo( $headers )
            )
            ->will(
                $this->returnValue( $json_encoded )
            );

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $container->expects( $this->at(0) )
            ->method('get')
            ->with( ServiceConstants::HTTP_CLIENT )
            ->will(
                $this->returnValue( $http_client )
            );

        $container->expects( $this->at(1) )
            ->method('get')
            ->with( HelperConstants::HELPER_FUNCTIONS  )
            ->will(
                $this->returnValue( $functions_helper )
            );

        $o_auth = new OAuthService( $container );
        $result = $o_auth->getAccessToken( $access_url );

        $this->assertEquals( $expected['access_token'], $result );

    }

}