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
use Services\LogService;

class LogServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testSetFile()
    {

        $file       = 'file.log';
        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $logger = new LogService( $container );

        $logger->setFile( $file );
        $result = $logger->getFile();

        $this->assertEquals( $file, $result );

    }

    /**
     * @test
     */
    public function testGetFile()
    {

        $file       = 'file.log';
        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $logger = new LogService( $container );

        $logger->setFile( $file );
        $result = $logger->getFile();

        $this->assertEquals( $file, $result );

    }

    /**
     * @test
     */
    public function testLog()
    {

        $message            = 'Logged message';
        $file               = 'file.log';
        $functions_helper   = $this->getMock( 'Helpers\FunctionHelper' );
        $functions_helper->expects( $this->once() )
            ->method('errorLog')
            ->with(
                $this->equalTo( $message ),
                $this->equalTo( 3 ),
                $this->equalTo( $file )
            );

        $container  = $this->getMockBuilder( 'Services\ContainerService' )
            ->getMock();

        $container->expects( $this->once() )
            ->method('get')
            ->with( HelperConstants::HELPER_FUNCTIONS  )
            ->will(
                $this->returnValue( $functions_helper )
            );

        $logger = new LogService( $container );

        $logger->setFile( $file );
        $logger->log( $message );

    }

}