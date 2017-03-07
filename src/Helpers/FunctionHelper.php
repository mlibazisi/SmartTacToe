<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Helpers;

/**
 * A wrapper for built-in functions
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class FunctionHelper
{

    /**
     * http_build_query wrapper
     *
     * @param array $data Query data
     *
     * @return string
     */
    public function httpBuildQuery( array $data ) {

        return http_build_query( $data );

    }

    /**
     * json_encode wrapper
     *
     * @param mixed $string Data to encode
     *
     * @return string Encoded string
     */
    public function jsonEncode( $string ) {

        return json_encode( $string );

    }

    /**
     * json_decode wrapper
     *
     * @param string    $string The string
     * @param bool      $assoc  Return associative array?
     *
     * @return string decoded string
     */
    public function jsonDecode( $string, $assoc = FALSE ) {

        return json_decode( $string, $assoc );

    }

    /**
     * rand wrapper
     *
     * @param int    $min Lower bound
     * @param int    $max Upper bound
     *
     * @return int
     */
    public function rand( $min, $max ) {

        return rand( $min, $max );

    }

    /**
     * array_merge wrapper
     *
     * @param array    $first   First array
     * @param array    $second  Second array
     *
     * @return array
     */
    public function arrayMerge( array $first, array $second ) {

        return array_merge( $first, $second );

    }

    /**
     * error_log wrapper
     *
     * @param string    $message    Message
     * @param int       $type       Log type
     * @param string    $file       File path
     *
     * @return bool
     */
    public function errorLog( $message, $type, $file ) {

        return error_log( $message, $type, $file );

    }

    /**
     * uniqid wrapper
     *
     * @return string A unique id
     */
    public function getUniqueId() {

        return uniqid();

    }

    /**
     * ob_end_flush wrapper
     *
     * @return bool
     */
    public function obEndFlush() {

        return ob_end_flush();

    }

    /**
     * fastcgi_finish_request wrapper
     *
     * @return bool
     */
    public function fastcgiFinishRequest() {

        return fastcgi_finish_request();

    }

    /**
     * headers_sent wrapper
     *
     * @return bool
     */
    public function headersSent() {

        return headers_sent();

    }

    /**
     * header wrapper
     *
     * @param string    $string     The header
     * @param bool      $replace    Replace true/false
     * @param int       $code       The code
     *
     * @return void
     */
    public function header( $string, $replace = TRUE, $code = NULL ) {

        header( $string, $replace, $code );

    }

    /**
     * function_exists wrapper
     *
     * @param string $string Function name
     *
     * @return bool
     */
    public function functionExists( $string ) {

        return function_exists( $string );

    }

    /**
     * array_shift wrapper
     *
     * @param array $array Array to shift
     *
     * @return mixed Shifted element
     */
    public function arrayShift( array &$array ) {

        return array_shift( $array );

    }

    /**
     * shuffle wrapper
     *
     * @param array $array Array to shuffle
     *
     * @return void
     */
    public function shuffle( array &$array ) {

        shuffle( $array );

    }

    /**
     * count wrapper
     *
     * @param array $array Array to count
     *
     * @return int
     */
    public function count( array $array ) {

        return count( $array );

    }

    /**
     * time wrapper
     *
     * @return int
     */
    public function time() {

        return time();

    }

    /**
     * uniqid wrapper
     *
     * @return string
     */
    public function uniqid() {

        return uniqid();

    }

    /**
     * implode wrapper
     *
     * @param string    $glue   The joiner
     * @param array     $pieces The array to implode
     *
     * @return string
     */
    public function implode( $glue, array $pieces ) {

        return implode( $glue, $pieces );

    }

    /**
     * strpos wrapper
     *
     * @param string $haystack  Haystack
     * @param string $needle    Needle
     *
     * @return int
     */
    public function strpos( $haystack, $needle ) {

        return strpos( $haystack, $needle );

    }

    /**
     * ltrim wrapper
     *
     * @param string $string  The string
     * @param string $chars   The char list to trim
     *
     * @return string
     */
    public function ltrim( $string, $chars ) {

        return ltrim( $string, $chars );

    }

    /**
     * realpath wrapper
     *
     * @param string $path  The path
     *
     * @return string
     */
    public function realpath( $path ) {

        return realpath( $path );

    }

}