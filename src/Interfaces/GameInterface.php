<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Interfaces;

use Services\GameService;

/**
 * The game server interface
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface GameInterface
{

    /**
     * Posts an error message to a channel
     *
     * @param string    $error_message The error message
     * @param bool      $replace_original  Whether to replace the original message or not
     * @return array
     */
    public function error( $error_message, $replace_original = FALSE );

    /**
     * Decline a game invitation
     *
     * @param array $challenger The user who initiated the challenge
     * @param array $opponent   The challenged user
     *
     * @return array
     */
    public function decline( $challenger, $opponent );

    /**
     * Delete any challenges that have not been responded to
     *
     * @param string    $channel_name   The current channel name
     * @param string    $channel_id     The current channel id
     * @return GameService
     */
    public function overridePendingChallenges( $channel_name, $channel_id );

    /**
     * Begin a game
     *
     * @param   array     $game_state     The game state
     * @param   string    $response_url   The response url
     * @return  bool True on success, false otherwise
     */
    public function begin( array $game_state, $response_url );

    /**
     * Delete a message from a channel
     *
     * @param array $params The deletion parameters
     * @return bool True on success, false otherwise
     */
    public function delete( $params );


    /**
     * Draws out the current state of the game
     *
     * @param array $state The game state
     *
     * @return string
     */
    public function drawBoard( array $state  );

    /**
     * Submit a play
     *
     * @param string    $me             The current user
     * @param array     $state          The ngame state
     * @param string    $channel_id     The id of the channel
     * @param string    $response_url   The url to respond to
     * @return bool Returns true on completion of a key step
     */
    public function play( $me, array $state, $channel_id, $response_url );

    /**
     * Retrieves the current game
     *
     * The current game is defined as the last message
     * in the channel history that was posted by this bot,
     * and matches certain search criteria in its text
     *
     * @param string    $channel_name       The current channel name
     * @param string    $channel_id         The current channel id
     * @return array
     */
    public function currentGame( $channel_name, $channel_id );

    /**
     * End any active game
     *
     * @param array     $me             The current user's info
     * @param string    $channel_name   The name of the channel
     * @param string    $channel_id     The id of the channel
     * @return mixed
     */
    public function end( $me, $channel_name, $channel_id );

    /**
     * View any active game
     *
     * @param string    $channel_name   The name of the channel
     * @param string    $channel_id     The id of the channel
     * @return mixed
     */
    public function status( $channel_name, $channel_id );

    /**
     * Determine if a game has ended
     *
     * @param array $board The game board
     * @return bool True if ended, false otherwise
     */
    public function isEnd( array $board );

    /**
     * Challenge an opponent
     *
     * @param string    $challenger The one challenging
     * @param string    $opponent   The one being challenged
     * @return array
     */
    public function challenge( $challenger, $opponent, $channel_id );

    /**
     * Determine if a move results in a win
     *
     * @param array    $linear_board  A one dimensional array of the board
     * @param string   $play          The ngame state
     * @return bool True if the result is a win, false otherwise
     */
    public function isWinningMove( array $linear_board, $play );
}