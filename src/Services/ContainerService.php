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

use Exceptions\ConfigurationException;
use Exceptions\ServiceException;

/**
 * Dependency Injection Container
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class ContainerService implements \ArrayAccess
{

    /**
     * The service object registry
     *
     * @var array
     */
    protected $_services = [];

    /**
     * Configuration parameters
     *
     * @var array
     */
    protected $_parameters = [];

    /**
     * Load the service container
     *
     * @param array $services   The services
     * @param array $parameters The configured parameters
     */
    public function __construct( array $services = [], array $parameters = [] )
    {
        $this->_services    = $services;
        $this->_parameters  = $parameters;

    }

    /**
     * Set the service
     *
     * @param string    $key     The service identifier
     * @param \Closure  $service The service
     *
     * @return void
     */
    public function offsetSet( $key, $service )
    {
        $this->_services[ $key ] = $service;
    }

    /**
     * Set the service
     *
     * @param   string $service The service identifier
     *
     * @return  \Closure
     * @throws  ServiceException
     */
    public function offsetGet( $service )
    {

        if( is_object( $this->_services[ $service ] )
            || method_exists( $this->_services[ $service ], '__invoke' ) ) {
            return $this->_services[ $service ]( $this );
        }

        $message = 'ContainerService::offsetGet failed to load service ' . $service;
        throw new ServiceException( $message );

    }

    /**
     * Check if a service exists
     *
     * @param   string $service The service identifier
     *
     * @return  bool
     */
    public function offsetExists( $service )
    {
        return isset( $this->_services[ $service ] );
    }

    /**
     * Removes a service from the registry
     *
     * @param   string $service The service identifier
     *
     * @return  void
     */
    public function offsetUnset( $service )
    {

        unset( $this->_services[ $service ] );

    }

    /**
     * Invokes a service
     *
     * @param string $service The service name
     *
     * @return mixed
     */
    public function get( $service  )
    {

        return $this->offsetGet( $service );

    }

    /**
     * Get a configured value from parameters.yml
     *
     * @param   string $key The sname of the parameter
     *
     * @return  string
     * @throws ConfigurationException
     */
    public function getParameter( $key  )
    {

        if ( strpos( $key, '.' ) ) {

            list( $outer_key, $inner_key ) = explode( '.', $key, 2 );

            if ( !isset( $this->_parameters[ $outer_key ][ $inner_key ] ) ) {
                $message = 'ContainerService::getParameters Parameter not set: ' . $key;
                throw new ConfigurationException( $message );
            }

            return $this->_parameters[ $outer_key ][ $inner_key ];

        }

        if ( !isset( $this->_parameters[ $key ] ) ) {
            $message = 'ContainerService::getParameters Parameter not set: ' . $key;
            throw new ConfigurationException( $message );
        }

        return $this->_parameters[ $key ];
    }

    /**
     * Set the service
     *
     * @param string    $key     The service identifier
     * @param \Closure  $service The service
     *
     * @return void
     */
    public function set( $key, $service )
    {

        $this->offsetSet( $key, $service );

    }

}