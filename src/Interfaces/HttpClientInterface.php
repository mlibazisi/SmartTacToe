<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Interfaces;

/**
 * The interface for HTTP clients
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface HttpClientInterface
{

    /**
     * Submit a POST http request
     *
     * @param string    $url        The url to post to
     * @param mixed     $data       The post data
     * @param array     $headers    Optional headers
     *
     * @return mixed
     */
    public function post( $url, $data, array $headers = [] );

    /**
     * Submit a JSON POST http request
     *
     * @param string    $url        The url to post to
     * @param array     $data       The data to post
     * @param array     $headers    Optional headers
     *
     * @return mixed
     */
    public function jsonPost( $url, array $data, array $headers = [] );

}