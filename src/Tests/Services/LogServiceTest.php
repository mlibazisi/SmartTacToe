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

use Services\LogService;

class LogServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testSetFile()
    {

        $file   = 'file.log';
        $logger = new LogService();
        $logger->setFile( $file );
        $result = $logger->getFile();

        $this->assertEquals( $file, $result );

    }

    /**
     * @test
     */
    public function testGetFile()
    {

        $file   = 'file.log';
        $logger = new LogService();
        $logger->setFile( $file );
        $result = $logger->getFile();

        $this->assertEquals( $file, $result );

    }

}