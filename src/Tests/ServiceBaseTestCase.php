<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Services {

    /**
     * Override curl_setopt() in Services namespace for testing
     *
     * @param \stdClass $handle the cURL resource
     * @param string    $name   The option name
     * @param mixed     $value  The option value
     * @return array An array of the signature
     */
    function curl_setopt( $handle, $name, $value )
    {

        return [
            $handle,
            $name,
            $value
        ];

    }

    /**
     * Override curl_init() in Services namespace for testing
     *
     * @param string $url A url string
     * @return \stdClass
     */
    function curl_init( $url = null )
    {

        return new \stdClass();

    }

    /**
     * Override curl_exec() in Services namespace for testing
     *
     * @param \stdClass $handle the cURL resource
     * @return bool
     */
    function curl_exec( $handle )
    {

        return true;

    }

    /**
     * Override curl_close() in Services namespace for testing
     *
     * @param \stdClass $handle the cURL resource
     * @return void
     */
    function curl_close( $handle )
    {
    }

}