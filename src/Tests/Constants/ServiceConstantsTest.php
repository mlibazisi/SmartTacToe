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

use Constants\ServiceConstants;

class ServiceConstantsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testLogConstant()
    {

        $this->assertEquals( 'log', ServiceConstants::LOG );

    }

    /**
     * @test
     */
    public function testRequestConstant()
    {

        $this->assertEquals( 'request', ServiceConstants::REQUEST );

    }

    /**
     * @test
     */
    public function testResponseConstant()
    {

        $this->assertEquals( 'response', ServiceConstants::RESPONSE );

    }

    /**
     * @test
     */
    public function testHttpClientConstant()
    {

        $this->assertEquals( 'http_client', ServiceConstants::HTTP_CLIENT );

    }

    /**
     * @test
     */
    public function testOAuthConstant()
    {

        $this->assertEquals( 'o_auth', ServiceConstants::OAUTH );

    }

    /**
     * @test
     */
    public function testGameServerConstant()
    {

        $this->assertEquals( 'game_server', ServiceConstants::GAME_SERVER );

    }

    /**
     * @test
     */
    public function testSearchConstant()
    {

        $this->assertEquals( 'search', ServiceConstants::SEARCH );

    }

    /**
     * @test
     */
    public function testOptimalPlayConstant()
    {

        $this->assertEquals( 'optimal_play', ServiceConstants::OPTIMAL_PLAY );

    }

}