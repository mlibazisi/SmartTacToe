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
use Interfaces\ResponseInterface;

/**
 * An HTTP response wrapper
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class ResponseService implements ResponseInterface
{

    /**
     * Maps HTTP codes to their meaning
     *
     * @var array
     */
    public static $status_texts = [
        200 => 'Ok',
        400 => 'Bad Request',
        500 => 'Internal Server Error'
    ];

    /**
     * HTTP Headers
     *
     * @var array
     */
    protected $_headers;

    /**
     * HTTP body
     *
     * @var string
     */
    protected $_content;

    /**
     * HTTP status code
     *
     * @var int
     */
    protected $_status_code;

    const SUCCESS_MESSAGE               = 'success';
    const ERROR_MESSAGE                 = 'error';
    const HTTP_OK                       = 200;
    const HTTP_BAD_REQUEST              = 400;
    const HTTP_INTERNAL_SERVER_ERROR    = 500;

    /**
     * Service and parameter container
     *
     * @var ContainerService
     */
    protected $_container;

    /**
     * The constructor
     *
     * @param ContainerService  $container      Dependency injection container
     * @param string            $content        The HTTP response body
     * @param int               $status_code    The HTTP response status code
     * @param array             $headers        The response headers
     */
    public function __construct( ContainerService $container, $content = '', $status_code = self::HTTP_OK, $headers = [] )
    {

        $this->_container = $container;

        $this->setHeaders( $headers );
        $this->setContent( $content );
        $this->setStatusCode( $status_code );

    }

    /**
     * Set custom HTTP [ header => value ] pairs
     *
     * @param array $headers An array of custom headers
     *
     * @return void
     */
    public function setHeaders( array $headers )
    {

        if ( !isset( $headers[ 'Content-Type' ] ) ) {
            $headers[ 'Content-Type' ] = 'text/html; charset=UTF-8';
        }

        $this->_headers = $headers;

    }

    /**
     * Get the currently set headers
     *
     * @return array
     */
    public function getHeaders()
    {

        return $this->_headers;

    }

    /**
     * Set the http response budy
     *
     * @param string $content The body
     *
     * @return void
     */
    public function setContent( $content )
    {

        $this->_content = (string)$content;

    }

    /**
     * Get the currently set content
     *
     * @return string
     */
    public function getContent()
    {

        return $this->_content;

    }

    /**
     * Set the http status code
     *
     * @param int $status_code The HTTP status code
     *
     * @return void
     */
    public function setStatusCode( $status_code )
    {

        $this->_status_code = $status_code;

    }

    /**
     * Get the currently set status code
     *
     * @return int
     */
    public function getStatusCode()
    {

        return $this->_status_code;

    }

    /**
     * Sends the http response to the requester
     *
     * @return void
     */
    public function send()
    {

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        if ( !$functions->headersSent() ) {

            foreach ( $this->_headers as $name => $value ) {
                $functions->header( "{$name}: {$value}", false, $this->_status_code );
            }

        }

        $this->_echo( $this->_content );

        if ( $functions->functionExists( 'fastcgi_finish_request' ) ) {
            $functions->fastcgiFinishRequest();
        } elseif ( PHP_SAPI != 'cli' ) {
            $functions->obEndFlush();
        }

    }

    /**
     * Print a string
     *
     * @param string $string The string the print
     *
     * @return void
     */
    private function _echo( $string = '' )
    {

        if ( $string ) {
            echo $string;
        }

    }

}