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
 * The interface for HTTP response wrappers
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface ResponseServiceInterface
{

    /**
     * The constructor
     *
     * @param string $content The HTTP response body
     * @param int $status_code The HTTP response status code
     * @param array $headers The response headers
     */
    public function __construct( $content = '', $status_code = 200, $headers = [] );

    /**
     * Set custom HTTP header => value pairs
     *
     * @param array $headers An array of custom headers
     * @return void
     */
    public function setHeaders( array $headers );

    /**
     * Get the currently set headers
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Set the http response budy
     *
     * @param string $content The body
     * @return void
     */
    public function setContent( $content );

    /**
     * Get the currently set content
     *
     * @return string
     */
    public function getContent();

    /**
     * Set the http status code
     *
     * @param int $status_code The body
     * @return void
     */
    public function setStatusCode( $status_code );

    /**
     * Get the currently set status code
     *
     * @return int
     */
    public function getStatusCode();

    /**
     * Sends the http response to the browser/client
     *
     * @return void
     */
    public function send();

}