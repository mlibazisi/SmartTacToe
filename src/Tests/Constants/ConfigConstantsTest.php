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

use Constants\ConfigConstants;

class ConfigConstantsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testSlackApiConstant()
    {

        $this->assertEquals( 'slack_api', ConfigConstants::SLACK_API );

    }

    /**
     * @test
     */
    public function testOauthUrlConstant()
    {

        $this->assertEquals( 'slack_api.oauth_access_url', ConfigConstants::OAUTH_ACCESS_URL );

    }

    /**
     * @test
     */
    public function testClientIdConstant()
    {

        $this->assertEquals( 'slack_api.client_id', ConfigConstants::CLIENT_ID );

    }

    /**
     * @test
     */
    public function testClientSecretConstant()
    {

        $this->assertEquals( 'slack_api.client_secret', ConfigConstants::CLIENT_SECRET );

    }

    /**
     * @test
     */
    public function testTokenConstant()
    {

        $this->assertEquals( 'slack_api.token', ConfigConstants::TOKEN );

    }

    /**
     * @test
     */
    public function testAccessTokenConstant()
    {

        $this->assertEquals( 'slack_api.access_token', ConfigConstants::ACCESS_TOKEN );

    }

    /**
     * @test
     */
    public function testSlackCommandConstant()
    {

        $this->assertEquals( 'slack_api.command', ConfigConstants::SLACK_COMMAND );

    }

    /**
     * @test
     */
    public function testPostMessageMethodConstant()
    {

        $this->assertEquals( 'slack_api.post_message_method', ConfigConstants::POST_MESSAGE_METHOD );

    }

    /**
     * @test
     */
    public function testSearchMessagesConstant()
    {

        $this->assertEquals( 'slack_api.search_messages', ConfigConstants::SEARCH_MESSAGES );

    }

    /**
     * @test
     */
    public function testDeleteMessagesConstant()
    {

        $this->assertEquals( 'slack_api.delete_message_method', ConfigConstants::DELETE_MESSAGE_METHOD );

    }

    /**
     * @test
     */
    public function testAppNameConstant()
    {

        $this->assertEquals( 'slack_api.app_name', ConfigConstants::APP_NAME );

    }

}