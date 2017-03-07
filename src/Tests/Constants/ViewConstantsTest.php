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

use Constants\ViewConstants;

class ViewConstantsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testoauthConstant()
    {

        $this->assertEquals( 'views/pages/oauth/index.html.php', ViewConstants::OAUTH );

    }

    /**
     * @test
     */
    public function testlayoutConstant()
    {

        $this->assertEquals( 'views/layouts/default.html.php', ViewConstants::LAYOUT );

    }

    /**
     * @test
     */
    public function testOauthErrorConstant()
    {

        $this->assertEquals( 'views/pages/oauth/error.html.php', ViewConstants::OAUTH_ERROR );

    }

    /**
     * @test
     */
    public function testHelpMessageConstant()
    {

        $this->assertEquals( 'views/elements/help.text.php', ViewConstants::HELP_MESSAGE );

    }

    /**
     * @test
     */
    public function testErrorMessageConstant()
    {

        $this->assertEquals( 'views/elements/error.text.php', ViewConstants::ERROR_MESSAGE );

    }

    /**
     * @test
     */
    public function testGameEndedConstant()
    {

        $this->assertEquals( 'views/elements/end.text.php', ViewConstants::GAME_ENDED );

    }

    /**
     * @test
     */
    public function testGameStatusConstant()
    {

        $this->assertEquals( 'views/elements/status.text.php', ViewConstants::GAME_STATUS );

    }

    /**
     * @test
     */
    public function testTimeOutConstant()
    {

        $this->assertEquals( 'views/elements/timeout.text.php', ViewConstants::TIME_OUT );

    }

    /**
     * @test
     */
    public function testDeclineConstant()
    {

        $this->assertEquals( 'views/elements/decline.text.php', ViewConstants::DECLINE );

    }

    /**
     * @test
     */
    public function testWinConstant()
    {

        $this->assertEquals( 'views/elements/win.text.php', ViewConstants::WIN );

    }

    /**
     * @test
     */
    public function testDrawConstant()
    {

        $this->assertEquals( 'views/elements/draw.text.php', ViewConstants::DRAW );

    }

    /**
     * @test
     */
    public function testGenericMessageConstant()
    {

        $this->assertEquals( 'views/elements/message.text.php', ViewConstants::GENERIC_MESSAGE );

    }

    /**
     * @test
     */
    public function testCurrentPlayConstant()
    {

        $this->assertEquals( 'views/elements/play.text.php', ViewConstants::CURRENT_PLAY );

    }

    /**
     * @test
     */
    public function testBoardTitleConstant()
    {

        $this->assertEquals( 'views/elements/board.text.php', ViewConstants::BOARD_TITLE );

    }

}