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

use Services\RequestService;

class RequestServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testQuery()
    {

        $query = [
            'page'      => 7,
            'total'     => 36
        ];

        $request = new RequestService();
        $request->setQuery( $query );

        $all_queries    = $request->query();
        $single_query   = $request->query( 'page' );

        $this->assertEquals( $query, $all_queries );
        $this->assertEquals( 7, $single_query );

    }

    /**
     * @test
     */
    public function testData()
    {

        $data = [
            'page'      => 7,
            'total'     => 36
        ];

        $request = new RequestService();
        $request->setData( $data );

        $all_data       = $request->data();
        $single_datum   = $request->data( 'total' );

        $this->assertEquals( $data, $all_data );
        $this->assertEquals( 36, $single_datum );

    }

    /**
     * @test
     */
    public function testGetPath()
    {

        $path = 'test/uri/path';

        $request = new RequestService();
        $request->setPath( $path );

        $result = $request->getPath();

        $this->assertEquals( $path, $result );

    }

    /**
     * @test
     */
    public function testGetPathWithBlankRequestUri()
    {

        $path = '';

        $request = new RequestService();
        $request->setPath( $path );

        $result = $request->getPath();

        $this->assertEquals( '/', $result );

    }

    /**
     * @test
     */
    public function testSetQuery()
    {

        $query = [
            'page'      => 563,
            'total'     => 36
        ];

        $request = new RequestService();
        $request->setQuery( $query );

        $all_queries    = $request->query();
        $single_query   = $request->query( 'page' );

        $this->assertEquals( $query, $all_queries );
        $this->assertEquals( 563, $single_query );

    }

    /**
     * @test
     */
    public function testSetData()
    {

        $data = [
            'page'      => 123,
            'total'     => 234
        ];

        $request = new RequestService();
        $request->setData( $data );

        $all_data       = $request->data();
        $single_datum   = $request->data( 'total' );

        $this->assertEquals( $data, $all_data );
        $this->assertEquals( 234, $single_datum );

    }

}