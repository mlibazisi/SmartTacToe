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

use Interfaces\RequestInterface;
use Interfaces\RequestServiceInterface;

/**
 * An HTTP request wrapper
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class RequestService implements RequestInterface
{

    /**
     * Holds $_POST parameters
     *
     * @var array
     */
    protected $_data;

    /**
     * Holds $_GET parameters
     *
     * @var array
     */
    protected $_query;

    /**
     * The URI path
     *
     * @var string
     */
    protected $_path;

    /**
     * Loads the request variables
     *
     * @param array     $data   The POST data
     * @param array     $query  The GET data
     * @param string    $path   The URI path
     */
    public function __construct( $data = [], $query = [], $path = '' )
    {

        $this->setData( $data );
        $this->setQuery( $query );
        $this->setPath( $path );

    }

    /**
     * Loads the request wrapper from
     * the global request variables
     *
     * @return RequestService
     */
    public static function instantiateFromGlobals()
    {

        $path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

        return new static( $_POST, $_GET, $path );

    }

    /**
     * Access a GET parameter
     *
     * Returns all $_GET params if $key is not specified,
     * otherwise it returns the $_GET parameter specified by $key
     *
     * @param   string $key The name of the GET parameter
     * @return  mixed Array of all parameters, or string
     */
    public function query( $key = NULL )
    {

        if ( $key == NULL ) {
            return (array)$this->_query;
        }

        return ( isset( $this->_query[ $key ] ) )
            ? $this->_query[ $key ]
            : NULL;

    }

    /**
     * Access a POST parameter
     *
     * Returns all $_POST params if $key is not specified,
     * otherwise it returns the $_POST parameter specified by $key
     *
     * @param   string $key The name of the POST parameter
     * @return  mixed Array of all parameters, or string
     */
    public function data( $key = NULL )
    {

        if ( $key == NULL ) {
            return (array)$this->_data;
        }

        return ( isset( $this->_data[ $key ] ) )
            ? $this->_data[ $key ]
            : NULL;

    }

    /**
     * Get the Request URI path
     *
     * @return string
     */
    public function getPath()
    {

        if ( !$this->_path ) {
            $this->_path = '/';
        }

        return $this->_path;

    }

    /**
     * Set the URI path
     *
     * @param   string $path The path
     * @return  void
     */
    public function setPath( $path )
    {

        $this->_path = $path;

    }

    /**
     * Set the POST data
     *
     * @param   array $data The POST data
     * @return  void
     */
    public function setData( array $data )
    {

        $this->_data = $data;

    }

    /**
     * Set the GET data
     *
     * @param   array $query The GET data
     * @return  void
     */
    public function setQuery( array $query )
    {

        $this->_query = $query;

    }

}