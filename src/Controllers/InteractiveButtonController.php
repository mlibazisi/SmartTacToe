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
use Services\ResponseService;
use Constants\ServiceConstants;

/**
 * Handles all incoming Interactive button actions
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class InteractiveButtonController extends \Controller
{

    /**
     * Maps interactive button actions to valid handlers
     *
     * @param Request $request The HTTP request object
     *
     * @return ResponseService
     * @throws ApiException
     */
    public function indexAction( Request $request )
    {

        try {
            $payload = $this->_getPayload( $request->data() );
        } catch ( ApiException $e ) {

            $message    = $e->getMessage();
            $logger     = $this->get( ServiceConstants::LOG );
            $logger->log( $message );

            throw new ApiException( $message );

        }

        $response_url   = $payload['response_url'];
        $me             = $payload['user'];
        $challenger     = $payload['challenger'];
        $opponent       = $payload['opponent'];
        $game           = $payload['game'];
        $channel_id     = $payload['channel']['id'];
        $response       = NULL;

        try {

            switch ( $payload['status'] ) {
                case GameConstants::DECLINED_ACTION:
                    $response = $this->_declineGame( $me, $challenger, $opponent );
                    break;
                case GameConstants::ACCEPTED_ACTION:

                    $response = $this->_beginGame( $me, $opponent, $game, $response_url );

                    if ( $response == FALSE ) {
                        $response = $this->_error( 'Something went wrong! Please try again.' );
                    }

                    break;
                case GameConstants::PLAYED_ACTION:
                    $response = $this->_playGame( $me, $game, $channel_id, $response_url );
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
     * Extract payload from slack request
     *
     * @param array $data Request from slack's interactive button
     *
     * @return array The payload contents
     * @throws ApiException
     */
    private function _getPayload( array $data )
    {

        if ( empty( $data['payload'] ) ) {
            throw new ApiException( "InteractiveButtonController::_getPayload missing payload parameter" );
        }

        $payload    = json_decode( $data['payload'], true );
        $token      = !empty( $payload['token'] )
            ? $payload['token']
            : NULL;

        $expected_token = $this->getParameter( ConfigConstants::TOKEN );

        if ( $expected_token != $token ) {
            throw new ApiException( "InteractiveButtonController::_getPayload Received token [{$token}] does not match token in parameters.yml" );
        }

        if ( empty( $payload['actions'][0]['value'] ) ) {
            throw new ApiException( "InteractiveButtonController::_getPayload invalid action value found in button response" );
        }

        $response                   = json_decode( $payload['actions'][0]['value'], TRUE );
        $response['user']           = $payload['user'];
        $response['channel']        = $payload['channel'];
        $response['response_url']   = $payload['response_url'];

        return $response;

    }

    /**
     * Begin a game
     *
     * @param array $me             User who clicked the button
     * @param array $opponent       The opponent in the game
     * @param array $game_state     The game state
     * @param array $response_url   The response url
     *
     * @return mixed True|False on success/fail, or array with message on error
     */
    private function _beginGame( array $me, array $opponent, array $game_state, $response_url ) {

        if ( $me['id'] != $opponent['id'] ) {
            return $this->_error( 'Only challenged players can accept invitations' );
        }

        return $this->get( ServiceConstants::GAME_SERVER )
            ->begin( $game_state, $response_url );

    }

    /**
     * Play a game
     *
     * @param array $me             User who clicked the button
     * @param array $game_state     The game state
     * @param array $channel_id     The id of the current channel
     * @param array $response_url   The response url
     *
     * @return mixed
     */
    private function _playGame( $me, array $game_state, $channel_id, $response_url ) {

        if ( count( $game_state['board'] ) == 9 ) {
            return $this->_error( 'This game has already ended' );
        }

        $current_player = $game_state['players'][0];

        if ( $me['id'] != $current_player['id'] ) {
            return $this->_error( 'It\'s not your turn to play. Patience is the virtue!' );
        }

        if ( !is_numeric( $game_state['play'] )
            || $game_state['play'] < 0
            || ( $game_state['play'] > 8 ) ) {
            return $this->_error( 'That was an invalid play' );
        }

        if ( isset( $game_state['board'][ $game_state['play'] ] ) ) {
            return $this->_error( 'That position has already been played! Choose another one!' );
        }

        return $this->get( ServiceConstants::GAME_SERVER )
            ->play( $me, $game_state, $channel_id, $response_url );

    }

    /**
     * Decline a game invitation
     *
     * @param array $me             User who clicked the button
     * @param array $challenger     The user who initiated the challenge
     * @param array $opponent       The challenged user
     *
     * @return array A response array with a message for the user
     */
    private function _declineGame( $me, $challenger, $opponent ) {

        if ( $me['id'] != $opponent['id'] ) {
            return $this->_error( 'Only challenged players can decline invitations' );
        }

        return $this->get( ServiceConstants::GAME_SERVER )
            ->decline( $challenger, $opponent );

    }

}