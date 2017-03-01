<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Interfaces;

/**
 * The interface for HTTP request wrappers
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface RequestInterface
{

    /**
     * Loads the request variables
     *
     * @param array     $data   The POST data
     * @param array     $query  The GET data
     * @param string    $path   The URI path
     */
    public function __construct( $data = [], $query = [], $path = '' );

    /**
     * Loads the request wrapper from
     * the global request variables
     *
     * @return \Services\RequestService
     */
    public static function instantiateFromGlobals();

    /**
     * Access a GET parameter
     *
     * Returns all $_GET params if $key is not specified,
     * otherwise it returns the $_GET parameter specified by $key
     *
     * @param   string $key The name of the GET parameter
     * @return  mixed Array of all parameters, or string
     */
    public function query( $key = NULL );

    /**
     * Access a POST parameter
     *
     * Returns all $_POST params if $key is not specified,
     * otherwise it returns the $_POST parameter specified by $key
     *
     * @param   string $key The name of the POST parameter
     * @return  mixed Array of all parameters, or string
     */
    public function data( $key = NULL );

    /**
     * Get the Request URI path
     *
     * @return string
     */
    public function getPath();

    /**
     * Set the URI path
     *
     * @param   string $path The path
     * @return  void
     */
    public function setPath( $path );

    /**
     * Set the POST data
     *
     * @param   array $data The POST data
     * @return  void
     */
    public function setData( array $data );

    /**
     * Set the GET data
     *
     * @param   array $query The GET data
     * @return  void
     */
    public function setQuery( array $query );

}