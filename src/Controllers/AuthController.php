<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Controllers;
use Constants\ConfigConstants;
use Constants\ServiceConstants;
use Constants\ViewConstants;
use Exceptions\ControllerException;
use Exceptions\ServiceException;
use Interfaces\RequestInterface as Request;
use Services\ResponseService;
use Exceptions\ConfigurationException;

/**
 * Controller to handle OAuth flow
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class AuthController extends \Controller
{

    /**
     * Handles slack's oauth redirect url
     *
     * @param Request $request The HTTP request object
     * @return ResponseService
     * @throws ControllerException
     */
    public function indexAction( Request $request )
    {

        $code = $request->query( 'code' );

        if ( !$code ) {

            $message    = 'AuthController::indexAction missing code required to get access_token';
            $logger     = $this->get( ServiceConstants::LOG );

            $logger->log( $message );

            return $this->response(
                $this->render( ViewConstants::OAUTH_ERROR )
            );

        }

        try {

            $access_url     = $this->_getAccessUrl( $code );
            $access_token   = $this->get( ServiceConstants::OAUTH )
                ->getAccessToken( $access_url );

        }
        catch ( ServiceException $e ) {

            return $this->response(
                $this->render( ViewConstants::OAUTH_ERROR )
            );

        }
        catch ( ConfigurationException $e ) {

            return $this->response(
                $this->render( ViewConstants::OAUTH_ERROR )
            );

        }

        return $this->response(
            $this->render( ViewConstants::OAUTH )
        );

    }

    /**
     * Get the url to request the access token
     *
     * @param string $code THe code to exchange for an access token
     * @return string
     */
    private function _getAccessUrl( $code ) {

        $access_url     = $this->getParameters( ConfigConstants::OAUTH_ACCESS_URL );
        $client_id      = $this->getParameters( ConfigConstants::CLIENT_ID );
        $client_secret  = $this->getParameters( ConfigConstants::CLIENT_SECRET );

        return sprintf( $access_url, $client_id, $client_secret, $code );

    }

}