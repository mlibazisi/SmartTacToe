<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use Services\ResponseService;

/**
 * The controller base class
 *
 * All controllers extend from this class, and this class
 * is not supposed to be instantiated.
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
abstract class Controller
{

    /**
     * Instantiates the HTTP Response Wrapper
     *
     * @param string    $content    The body of the response
     * @param int       $status     The HTTP response status code
     * @params array    $headers    Optional Custom headers
     * @return ResponseService
     */
    public function response( $content, $status = ResponseService::HTTP_OK, $headers = [] )
    {

        return new ResponseService( $content, $status, $headers );

    }

    /**
     * A Controller::response wrapper for JSON responses
     *
     * @param mixed     $content    The body of the response
     * @param int       $status     The HTTP response status code
     * @params array    $headers    Optional Custom headers
     * @return ResponseService
     */
    public function jsonResponse( $content, $status = ResponseService::HTTP_OK, $headers = [] )
    {

        $headers = array_merge( $headers, [
            'Content-Type' => 'application/json'
        ] );

        $status_message = ( $status >= ResponseService::HTTP_OK
            && $status < ResponseService::HTTP_BAD_REQUEST )
            ? ResponseService::SUCCESS_MESSAGE
            : ResponseService::ERROR_MESSAGE;

        $content = [
            'status'    => $status_message,
            'data'      => $content
        ];

        return $this->response( json_encode( $content ), $status, $headers );

    }


}
