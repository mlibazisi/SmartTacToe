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

class ContainerServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testOffsetSet()
    {

        $expected = function () {
            return new \stdClass();
        };

        $container = new ContainerService( [] );
        $container->offsetSet( 'Request', $expected );
        $result = $container->offsetGet( 'Request' );

        $this->assertInstanceOf( \stdClass::class, $result );

    }

    /**
     * @test
     */
    public function testOffsetExists()
    {

        $expected = function () {
            return new \stdClass();
        };

        $container = new ContainerService( [] );
        $container->offsetSet( 'Request', $expected );
        $result = $container->offsetExists( 'Request' );

        $this->assertTrue( $result );

    }

    /**
     * @test
     */
    public function testOffsetGet()
    {

        $expected = function () {
            return new \stdClass();
        };

        $container = new ContainerService( [] );
        $container->offsetSet( 'Request', $expected );
        $result = $container->offsetGet( 'Request' );

        $this->assertInstanceOf( \stdClass::class, $result );

    }

    /**
     * @test
     */
    public function testOffsetUnset()
    {

        $expected = function () {
            return new \stdClass();
        };

        $container = new ContainerService( [] );
        $container->offsetSet( 'Request', $expected );
        $result = $container->offsetExists( 'Request' );

        $this->assertTrue( $result );

        $container->offsetUnset( 'Request' );
        $result = $container->offsetExists( 'Request' );

        $this->assertFalse( $result );

    }

    /**
     * @test
     */
    public function testSet()
    {

        $expected = function () {
            return new \stdClass();
        };

        $container = new ContainerService( [] );
        $container->set( 'Request', $expected );
        $result = $container->offsetGet( 'Request' );

        $this->assertInstanceOf( \stdClass::class, $result );

    }

    /**
     * @test
     */
    public function testGet()
    {

        $expected = function () {
            return new \stdClass();
        };

        $container = new ContainerService( [] );
        $container->offsetSet( 'Request', $expected );
        $result = $container->get( 'Request' );

        $this->assertInstanceOf( \stdClass::class, $result );

    }

}