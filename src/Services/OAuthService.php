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

use Constants\ServiceConstants;
use Interfaces\OAuthInterface;
use Exceptions\ServiceException;

/**
 * Handles OAuth interactions
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class OAuthService implements OAuthInterface
{

    /**
     * Service container
     *
     * @var ContainerService
     */
    protected $_container;

    /**
     * Instantiate service and inject the service container
     *
     * @param ContainerService $container The service container
     */
    public function __construct( ContainerService $container )
    {

        $this->_container = $container;

    }

    /**
     * Request an access token from an OAuth server
     *
     * @param string    $access_url     The url to submit the request
     * @return mixed
     * @throws ServiceException
     */
    public function getAccessToken( $access_url )
    {

        $http_client    = $this->_container->get( ServiceConstants::HTTP_CLIENT );
        $response       = $http_client->get( $access_url, [
            'Accept' => 'application/json'
        ] );

        $response = json_decode( $response, true );

        if ( empty( $response['access_token'] ) ) {
            throw new ServiceException( 'OAuthService::loadAccessToken Failed to load access_token' );
        }

        return $response['access_token'];

    }

}