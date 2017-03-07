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

use Constants\ConfigConstants;
use Constants\HelperConstants;
use Constants\ServiceConstants;
use Interfaces\SearchInterface;

/**
 * A Slack API message search client
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class SearchService implements SearchInterface
{

    /**
     * Service and parameter container
     *
     * @var ContainerService
     */
    protected $_container;

    /**
     * Instantiate SearchService and inject the service container
     *
     * @param ContainerService $container The service container
     */
    public function __construct( ContainerService $container )
    {

        $this->_container = $container;

    }

    /**
     * Performs a message search against the SLACK API
     *
     * @param string    $channel    The channel name to search
     * @param string    $text       The text to search for
     * @param int       $limit      The result set limit
     *
     * @return array The result set
     */
    public function find( $channel, $text, $limit = 20 )
    {

        $search_url     = $this->_container
            ->getParameter( ConfigConstants::SEARCH_MESSAGES );

        $query['token'] = $this->_container
            ->getParameter( ConfigConstants::ACCESS_TOKEN );

        $bot_name       = $this->_container
            ->getParameter( ConfigConstants::APP_NAME );

        $params         = [
            "in:{$channel}",
            "from:{$bot_name}"
        ];

        $params[]           = $text;
        $query['sort']      = 'timestamp';
        $query['sort_dir']  = 'desc';
        $query['count']     = $limit;

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $query['query']     = $functions->implode( ' ', $params );
        $search_url         .= '?' . $functions->httpBuildQuery( $query );
        $headers            = [
            'Accept' => 'application/json'
        ];

        $response = $this->_container
            ->get( ServiceConstants::HTTP_CLIENT )
            ->get( $search_url, $headers );

        $response = $functions->jsonDecode( $response, TRUE );

        if ( isset( $response['ok'] )
        && ( $response['ok'] == TRUE ) ) {

            return ( !empty( $response['messages']['matches'] ) )
                ? $response['messages']['matches']
                : [];

        }

        $message = 'SearchService::find() ' . $functions->jsonEncode( $response );
        $this->_container
            ->get( ServiceConstants::LOG )
            ->log( $message );

        return [];

    }

}