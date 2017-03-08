<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Services;

use Constants\HelperConstants;
use Interfaces\HttpClientInterface;

/**
 * A cURL based HTTP client
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
     * Service and parameter container
     *
     * @var ContainerService
     */
    protected $_container;

    /**
     * Instantiate HttpClientService and inject the service container
     *
     * @param ContainerService $container The service container
     */
    public function __construct( ContainerService $container )
    {

        $this->_container = $container;

    }

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
     * @return array
     */
    public function jsonPost( $url, array $data, array $headers = [] )
    {

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $json_string    = $functions->jsonEncode( $data );
        $headers        = $functions->arrayMerge( $headers, [
            'Content-Type'      => 'application/json',
            'Content-Length'    => strlen( $json_string )
        ] );

        $response = $this->post( $url, $json_string, $headers );

        if ( $response ) {
            return $functions->jsonDecode( $response, TRUE );
        }

        return [];

    }

    /**
     * Submit a GET http request
     *
     * @param string    $url        The url to get
     * @param array     $headers    Optional headers
     *
     * @return mixed
     */
    public function get( $url, array $headers = [] )
    {

        $this->init( $url );

        $this->setOption( CURLOPT_CRLF, TRUE );
        $this->setOption( CURLOPT_RETURNTRANSFER, TRUE );

        if ( $headers ) {
            $this->setOption( CURLOPT_HTTPHEADER, $headers );
        }

        $response = $this->execute();
        $this->close();

        return $response;
    }

    /**
     * Set a cURL option
     *
     * @param string    $name   The option name
     * @param mixed     $value  The option value
     *
     * @return bool true on success , false on failure
     */
    public function setOption( $name, $value )
    {

        return \curl_setopt( $this->_handle, $name, $value );

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

        return \curl_exec( $this->_handle );

    }

    /**
     * Close a cURL resource
     *
     * @return void
     */
    public function close()
    {

        \curl_close( $this->_handle );

    }

    /**
     * cURL init
     *
     * @return void
     */
    public function init( $url  = '')
    {

        $this->_handle = ( $url )
            ? \curl_init( $url )
            : \curl_init();

    }

    /**
     * Make sure the cURL resource is closed
     *
     * @return void
     */
    public function __destruct()
    {

        if ( \is_resource( $this->_handle ) ) {
            $this->close();
        }

    }

}