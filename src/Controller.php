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
use Exceptions\ServiceException;
use Exceptions\ControllerException;
use Constants\ServiceConstants;
use Exceptions\ConfigurationException;
use Constants\ViewConstants;
use Services\ContainerService;

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
     * Service container
     *
     * @var ContainerService
     */
    private $_container;

    /**
     * Configured parameters
     *
     * @var array
     */
    private $_parameters;

    /**
     * Sets the service container
     *
     * @param ContainerService $container The container
     * @return $this
     */
    public function setContainer( ContainerService $container ) {

        $this->_container = $container;

        return $this;

    }

    /**
     * Set parameters from the configuration directory
     *
     * @param array $parameters Configuration parameters
     * @return $this
     */
    public function setParameters( array $parameters )
    {

        $this->_parameters = $parameters;

        return $this;

    }

    /**
     * Set parameters from the configuration directory
     *
     * @param string $key Name of the parameter
     * @return mixed Returns string if key is specified, else array
     * @throws ConfigurationException
     */
    public function getParameters( $key = NULL )
    {

        if ( !$key ) {
            return $this->_parameters;
        }

        if ( strpos( $key, '.' ) ) {

            list( $outer_key, $inner_key ) = explode( '.', $key, 2 );

            if ( !isset( $this->_parameters[ $outer_key ][ $inner_key ] ) ) {

                $message    = 'Controller::getParameters Parameter not set: ' . $key;
                $logger     = $this->get( ServiceConstants::LOG );
                $logger->log( $message );
                throw new ConfigurationException( $message );

            }

            return $this->_parameters[ $outer_key ][ $inner_key ];

        }

        if ( !isset( $this->_parameters[ $key ] ) ) {

            $message    = 'Controller::getParameters Parameter not set: ' . $key;
            $logger     = $this->get( ServiceConstants::LOG );
            $logger->log( $message );
            throw new ConfigurationException( $message );

        }

        return $this->_parameters[ $key ];

    }

    /**
     * Renders a complete view
     *
     * Renders a view with corresponding
     * HTML header and body tags
     *
     * @param string    $page   The path to the page view
     * @param array     $vars   Variables to be accessed in the view
     * @return string   The rendered page
     * @throws ControllerException
     */
    public function render( $page, array $vars = [] )
    {

        $html_body = $this->element( $page, $vars );

        return $this->element( ViewConstants::LAYOUT, [
            '__LAYOUT_BODY__' => $html_body
        ] );

    }

    /**
     * Render a partial view
     *
     * Renders a view without appending the
     * HTML header and body tags
     *
     * @param string    $element   The path to the view
     * @param array     $vars       Variables to be accessed in the element
     * @return string   The rendered element
     * @throws ControllerException
     */
    public function element( $element, array $vars = [] )
    {

        $file = realpath( WEB_ROOT . '/../' . ltrim( $element, '/' ) );

        if ( !$file ) {

            $message    = 'Controller::element could not find file: ' . $file;
            $logger     = $this->get( \Constants\ServiceConstants::LOG );
            $logger->log( $message );
            throw new ControllerException( $message );

        }

        if ( $vars ) {
            extract( $vars, EXTR_SKIP );
        }

        ob_start();

        require $file;

        return ob_get_clean();

    }

    /**
     * Instantiates a service
     *
     * @param string $service The service name
     * @return mixed
     * @throws ServiceException
     */
    public function get( $service  )
    {

        try {

            return $this->_container->get( $service );

        } catch ( ServiceException $e ) {

            $message    = 'Controller::get failed to load service ' . $service;
            $logger     = new \Services\LogService();
            $logger->log( $message );

            throw new ServiceException( 'Controller::get Service not found: ' . $service );

        }

    }

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

        return $this->response( json_encode( $content ), $status, $headers );

    }


}
