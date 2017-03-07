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
     * @param string $access_url The url to submit the request
     *
     * @return string The token
     * @throws ServiceException
     */
    public function getAccessToken( $access_url )
    {

        $response = $this->_container
            ->get( ServiceConstants::HTTP_CLIENT )
            ->get( $access_url, [
            'Accept' => 'application/json'
        ] );

        $functions   = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $response   = $functions->jsonDecode( $response, true );

        if ( empty( $response['access_token'] ) ) {
            throw new ServiceException( 'OAuthService::getAccessToken Failed to get access_token' );
        }

        return $response['access_token'];

    }

}