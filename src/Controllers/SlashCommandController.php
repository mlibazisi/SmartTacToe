<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Controllers;
use Constants\ConfigConstants;
use Constants\GameConstants;
use Exceptions\ApiException;
use Interfaces\RequestInterface as Request;
use Constants\ServiceConstants;

/**
 * Handles all incoming /ttt Slash commands
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class SlashCommandController extends \Controller
{

    /**
     * White-list of expected $_POST fields
     *
     * @var array
     */
    private $_required_params = [
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
     * Maps slash commands to appropriate handlers
     *
     * Maps slash commands to appropriate handlers. If an exception
     * bubbles up to this level, the function will catch the exception and
     * post a friendly error message to the user. At this point,
     * there is no need to do anything else with the exception
     * because it will already be logged at the level where it occurred
     *
     * @param Request $request The HTTP request object
     *
     * @return mixed Returns ResponseService if there's an appropriate response, else void
     * @throws ApiException
     */
    public function indexAction( Request $request )
    {

        $valid_post_data    = $this->_validatePostData(
            $request->data()
        );

        $channel_name       = $valid_post_data['channel_name'];
        $channel_id         = $valid_post_data['channel_id'];
        $response           = NULL;
        $opponent           = [];
        $action             = '';
        $me                 = [
            'id'   => $valid_post_data['user_id'],
            'name' => $valid_post_data['user_name']
        ];

        if ( !empty( $valid_post_data['text'] ) ) {

            $slash_command_parts    = explode( ' ', $valid_post_data['text'], 2 );
            $action                 = trim( $slash_command_parts[0] );

            if ( !empty( $slash_command_parts[1] ) ) {

                $opponent = $this->_extractUserFromHandle(
                    $slash_command_parts[1]
                );

            }

        }

        try {

            switch ( $action ) {

                case '':
                case GameConstants::HELP_ACTION:
                    $response = $this->_showHelpOptions();
                    break;
                case GameConstants::CHALLENGE_ACTION:
                    $response = $this->_challengeOpponent( $me, $opponent, $channel_name, $channel_id );
                    break;
                case GameConstants::STATUS_ACTION:
                    $response = $this->_viewCurrentGame( $channel_name, $channel_id );
                    break;
                case GameConstants::END_ACTION:
                    $response = $this->_endCurrentGame( $me, $channel_name, $channel_id );
                    break;
                default:
                    $response = $this->_error( 'You typed an invalid slash command!' );
                    break;

            }

        }
        catch ( ApiException $e )
        {
            $response = $this->_error( 'Something went wrong! Please try again.' );
        }
        catch ( \Exception $e )
        {
            $response = $this->_error( 'Something went wrong! Please try again.' );
        }

        if ( is_array( $response ) ) {
            return $this->jsonResponse( $response );
        }

    }

    /**
     * Gets the list of help options
     *
     * @return array The help options
     */
    private function _showHelpOptions()
    {

        return $this->get( ServiceConstants::GAME_SERVER )
            ->help();

    }

    /**
     * Challenge an opponent
     *
     * @param array     $challenger     The challenger user array
     * @param string    $opponent       The opponent user array
     * @param string    $channel_name   The current channel name
     * @param string    $channel_id     The current channel id
     *
     * @return mixed
     */
    private function _challengeOpponent( $challenger, $opponent, $channel_name, $channel_id )
    {

        if ( empty( $opponent ) ) {
            return $this->_error( 'Please enter an opponent to challenge' );
        }

        if ( $challenger['id'] == $opponent['id'] ) {
            return $this->_error( 'You can\'t challenge yourself. Choose another opponent!' );
        }

        $current_game = $this->_getCurrentGame( $channel_name, $channel_id );

        if ( $current_game ) {
            return $this->_error( 'There\'s a game already in session! Only one game per channel is allowed.' );
        }

        return $this->get( ServiceConstants::GAME_SERVER )
            ->overridePendingChallenges( $channel_name, $channel_id )
            ->challenge( $challenger, $opponent, $channel_id );

    }

    /**
     * Retrieves the current game
     *
     * The current game is defined as the last message
     * in the channel history that was posted by this bot,
     * and matches certain search criteria in its text
     *
     * @param string    $channel_name       The current channel name
     * @param string    $channel_id         The current channel id
     *
     * @return array A response array with the current game
     */
    private function _getCurrentGame( $channel_name, $channel_id )
    {

        return $this->get( ServiceConstants::GAME_SERVER )
            ->currentGame( $channel_name, $channel_id );

    }

    /**
     * Extract only white-listed $_POST parameters
     *
     * Extracts only white-listed $_POST parameters and
     * throws an exception if any parameter is missing, or if
     * the token and command do not match what this controller is
     * configured to handle
     *
     * @param array $data The $_POST data
     *
     * @return array The white listed parameters
     * @throws ApiException
     */
    private function _validatePostData( array $data )
    {

        $valid_data = [];

        foreach ( $this->_required_params as $field ) {

            if ( !isset( $data[ $field ] ) ) {

                $message = "SlackCommandController::_validatePostData Received _POST data missing {$field} parameter";
                $this->get( ServiceConstants::LOG )
                    ->log( $message );

                throw new ApiException( $message );

            }

            $valid_data[ $field ] = $data[ $field ];

        }

        $token          = $valid_data['token'];
        $expected_token = $this->getParameter( ConfigConstants::TOKEN );

        if ( $expected_token != $token ) {

            $message = "SlackCommandController::_validatePostData Received token [{$token}] does not match token configured in parameters.yml";
            $this->get( ServiceConstants::LOG )
                ->log( $message );

            throw new ApiException( $message );

        }

        $command            = $valid_data['command'];
        $expected_command   = $this->getParameter( ConfigConstants::SLACK_COMMAND );

        if ( $command != $expected_command ) {

            $message = "SlackCommandController::_validatePostData Received command [{$command}] does not match expected [{$expected_command}] command configured in parameters.yml";
            $this->get( ServiceConstants::LOG )
                ->log( $message );

            throw new ApiException( $message );

        }

        return $valid_data;

    }

    /**
     * End any active game
     *
     * @param array     $me             The current user's info
     * @param string    $channel_name   The name of the channel
     * @param string    $channel_id     The id of the channel
     *
     * @return array An array with a status message of te action
     */
    private function _endCurrentGame( $me, $channel_name, $channel_id ) {

        return $this->get( ServiceConstants::GAME_SERVER )
            ->end( $me, $channel_name, $channel_id  );

    }

    /**
     * View any active game
     *
     * @param string    $channel_name   The name of the channel
     * @param string    $channel_id     The id of the channel
     *
     * @return array A response array with the game info
     */
    private function _viewCurrentGame( $channel_name, $channel_id )
    {

        return $this->get( ServiceConstants::GAME_SERVER )
            ->status( $channel_name, $channel_id );

    }

}