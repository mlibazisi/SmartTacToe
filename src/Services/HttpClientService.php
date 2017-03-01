<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Services;

use Interfaces\HttpClientInterface;

/**
 * An cURL based HTTP client
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class HttpClientService implements HttpClientInterface
{

    /**
     * A cURL resource
     *
     * @var resource
     */
    private $_handle;

    /**
     * Connection timeout in seconds
     *
     * @var int
     */
    const CONNECTION_TIMEOUT = 5;

    /**
     * Submit a POST http request
     *
     * @param string    $url        The url to post to
     * @param mixed     $data       The post data
     * @param array     $headers    Optional headers
     *
     * @return mixed
     */
    public function post( $url, $data, array $headers = [] )
    {

        $this->init( $url );

        $this->setOption( CURLOPT_CUSTOMREQUEST, 'POST' );
        $this->setOption( CURLOPT_POSTFIELDS, $data );
        $this->setOption( CURLOPT_CRLF, TRUE );
        $this->setOption( CURLOPT_RETURNTRANSFER, TRUE );
        $this->setOption( CURLOPT_HTTPHEADER, $headers );

        $response = $this->execute();
        $this->close();

        return $response;

    }

    /**
     * Submit a JSON POST http request
     *
     * @param string    $url        The url to post to
     * @param array     $data       The data to post
     * @param array     $headers    Optional headers
     *
     * @return mixed
     */
    public function jsonPost( $url, array $data, array $headers = [] )
    {

        $json_string    = json_encode( $data );
        $headers        = array_merge( $headers, [
            'Content-Type'      => 'application/json',
            'Content-Length'    => strlen( $json_string )
        ] );

        return $this->post( $url, $json_string, $headers );

    }

    /**
     * Submit a GET http request
     *
     * @param string $url The url to get
     *
     * @return mixed
     */
    public function get( $url )
    {

        $this->init( $url );

        if ( $url ) {
            $this->setOption( CURLOPT_URL, $url );
        }

        $this->setOption( CURLOPT_RETURNTRANSFER, 1 );
        $this->setOption( CURLOPT_CONNECTTIMEOUT,
            self::CONNECTION_TIMEOUT );

        $response = $this->execute();
        $this->close();

        return $response;

    }

    /**
     * Set a cURL option
     *
     * @param string    $name   The option name
     * @param mixed     $value  The option value
     * @return bool true on success , false on failure
     */
    public function setOption( $name, $value )
    {

        return curl_setopt( $this->_handle, $name, $value );

    }

    /**
     * Get the cURL resource
     *
     * @return resource
     */
    public function getHandle()
    {

        return $this->_handle;

    }

    /**
     * Execute a cURL request
     *
     * @return mixed
     */
    public function execute()
    {

        return curl_exec( $this->_handle );

    }

    /**
     * Close a cURL resource
     *
     * @return void
     */
    public function close()
    {

        curl_close( $this->_handle );

    }

    /**
     * cURL init
     *
     * @return void
     */
    public function init( $url )
    {

        $this->_handle = curl_init( $url );

    }

    /**
     * Make sure the cURL resource is closed
     *
     * @return void
     */
    public function __destruct()
    {

        if ( is_resource( $this->_handle ) ) {
            $this->close();
        }

    }

}