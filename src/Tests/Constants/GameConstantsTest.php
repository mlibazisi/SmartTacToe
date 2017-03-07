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

use Constants\GameConstants;

class GameConstantsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testChallengedStatusConstant()
    {

        $this->assertEquals( 0, GameConstants::CHALLENGED_STATUS );

    }

    /**
     * @test
     */
    public function testXMarkerConstant()
    {

        $this->assertEquals( 'X', GameConstants::X_MARKER );

    }

    /**
     * @test
     */
    public function testOMarkerConstant()
    {

        $this->assertEquals( 'O', GameConstants::O_MARKER );

    }

    /**
     * @test
     */
    public function testBlankMarkerConstant()
    {

        $this->assertEquals( 'mouse', GameConstants::BLANK_MARKER );

    }

    /**
     * @test
     */
    public function testActiveGameConstant()
    {

        $this->assertEquals( 'You\'re playing as', GameConstants::ACTIVE_GAME_TEXT );

    }

    /**
     * @test
     */
    public function testChallengeTextConstant()
    {

        $this->assertEquals( 'Wanna play SmartTacToe against', GameConstants::CHALLENGE_TEXT );

    }

    /**
     * @test
     */
    public function testTimeoutConstant()
    {

        $this->assertEquals( 'game_timeout', GameConstants::GAME_TIMEOUT );

    }

    /**
     * @test
     */
    public function testHelpActionConstant()
    {

        $this->assertEquals( 'help', GameConstants::HELP_ACTION );

    }

    /**
     * @test
     */
    public function testChallengeActionConstant()
    {

        $this->assertEquals( 'challenge', GameConstants::CHALLENGE_ACTION );

    }

    /**
     * @test
     */
    public function testStatusActionConstant()
    {

        $this->assertEquals( 'status', GameConstants::STATUS_ACTION );

    }

    /**
     * @test
     */
    public function testEndActionConstant()
    {

        $this->assertEquals( 'end', GameConstants::END_ACTION );

    }

    /**
     * @test
     */
    public function testDeclinedActionConstant()
    {

        $this->assertEquals( 'declined', GameConstants::DECLINED_ACTION );

    }

    /**
     * @test
     */
    public function testAcceptedActionConstant()
    {

        $this->assertEquals( 'accepted', GameConstants::ACCEPTED_ACTION );

    }

    /**
     * @test
     */
    public function testPlayedActionConstant()
    {

        $this->assertEquals( 'played', GameConstants::PLAYED_ACTION );

    }

}