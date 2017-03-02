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

use Exceptions\ServiceException;

/**
 * Acts as a service registry
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
     * Load the service container
     *
     * @param array $services The services
     */
    public function __construct( array $services = [] )
    {
        $this->_services = $services;

    }

    /**
     * Set the service
     *
     * @param string    $key     The service identifier
     * @param \Closure  $service The service
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
     * @return  \Closure
     * @throws  ServiceException
     */
    public function offsetGet( $service )
    {

        if( is_object( $this->_services[ $service ] )
            || method_exists( $this->_services[ $service ], '__invoke' ) ) {
            return $this->_services[ $service ]( $this );
        }

        throw new ServiceException( 'ContainerService::offsetGet Service not found' );

    }

    /**
     * Check if a service exists
     *
     * @param   string $service The service identifier
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
     * @return mixed
     */
    public function get( $service  )
    {

        return $this->offsetGet( $service );

    }

    /**
     * Set the service
     *
     * @param string    $key     The service identifier
     * @param \Closure    $service The service
     * @return void
     */
    public function set( $key, $service )
    {

        $this->offsetSet( $key, $service );

    }

}