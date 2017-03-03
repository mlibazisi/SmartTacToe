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
use Exceptions\ControllerException;
use Interfaces\RequestInterface as Request;
use Services\ResponseService;
use Constants\ServiceConstants;

/**
 * Handles all incoming Slash commands
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class SlashCommandController extends \Controller
{

    /**
     * White-list of acceptable $_POST fields
     *
     * @var array
     */
    private $_command_params = [
        'token',
        'team_id',
        'team_domain',
        'channel_id',
        'channel_name',
        'user_id',
        'user_name',
        'command',
        'text',
        'response_url'
    ];

    /**
     * Maps slash commands to valid handlers
     *
     * @param Request $request The HTTP request object
     * @return ResponseService
     * @throws ControllerException
     */
    public function indexAction( Request $request )
    {

        $raw_post_data   = $request->data();
        $valid_post_data = $this->_validatePostData( $raw_post_data );

        if ( !$valid_post_data ) {

            $message    = 'SlackCommandController::indexAction Invalid slash command params';
            $logger     = $this->get( ServiceConstants::LOG );
            $logger->log( $message );

            throw new ControllerException( $message );

        }

        $response_url   = $valid_post_data['response_url'];
        $user_id        = $valid_post_data['user_id'];
        $user_name      = $valid_post_data['user_name'];
        $me             = "<@{$user_id}|{$user_name}>";
        $opponent       = NULL;
        $action         = '';

        if ( !empty( $valid_post_data['text'] ) ) {

            $command_parts  = explode( ' ', $valid_post_data['text'], 2 );
            $action         = trim( $command_parts[0] );

            if ( !empty( $command_parts[1] ) ) {
                $opponent = trim( $command_parts[1] );
            }

        }

        switch ( $action ) {

            case '':
            case 'help':
                $response = $this->_getHelpOptions();
                break;
            default:
                $response = $this->_getErrorMessage( 'Invalid slash command' );
                break;

        }

        $this->get( ServiceConstants::HTTP_CLIENT )
            ->jsonPost( $response_url, $response );

    }

    /**
     * Gets and formats the help options for the a user
     *
     * @param string $title Optional title for help options
     * @return array
     */
    private function _getHelpOptions( $title = '')
    {

        if ( empty( $title ) ) {
            $title = ":smile: *Welcome to TicTacToezy*\n";
        }

        $response = $this->get( ServiceConstants::GAME_SERVER )
            ->help();

        $response['text'] = $this->element(
            'views/elements/help.text.php', [
            'title' => $title
        ] );

        return $response;

    }

    /**
     * Gets and formats the error message for the user
     *
     * @param string $message The error message
     * @return array
     */
    private function _getErrorMessage( $message )
    {

        return $this->_getHelpOptions( "*oOps!* {$message}\n" );

    }

    /**
     * Extract only valid $_POST data
     *
     * @param array $data The $_POST data
     * @return array
     * @throws ControllerException
     */
    private function _validatePostData( array $data )
    {

        $valid_data = [];

        foreach ( $this->_command_params as $field ) {

            if ( !isset( $data[ $field ] ) ) {
                return [];
            }

            $valid_data[ $field ] = $data[ $field ];

        }

        $command            = $valid_data['command'];
        $expected_command   = $this->getParameters( ConfigConstants::SLACK_COMMAND );

        if ( $command != $expected_command ) {

            $message    = "SlackCommandController::_validatePostData Received command [{$command}] does not match expected [{$expected_command}]";
            $logger     = $this->get( ServiceConstants::LOG );
            $logger->log( $message );

            throw new ControllerException( $message );

        }

        return $valid_data;

    }

}