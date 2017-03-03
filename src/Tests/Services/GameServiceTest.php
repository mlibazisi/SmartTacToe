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

use Services\GameService;

class GameServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testHelp()
    {

        $expected = [
            "response_type" => 'ephemeral',
            "mrkdwn"        => true
        ];

        $game       = new GameService();
        $response   = $game->help();

        $this->assertEquals( $expected, $response );

    }

}