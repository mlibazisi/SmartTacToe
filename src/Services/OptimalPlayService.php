<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Services;

use Constants\GameConstants;
use Interfaces\GameInterface;
use Interfaces\OptimalPlayInterface;

/**
 * Determines an Optimal Play
 *
 * A mimetic of the MiniMax Game
 * Theory Algorithm to predict optimal moves
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class OptimalPlayService implements OptimalPlayInterface
{

    /**
     * A player marker, either X|O
     *
     * @var string
     */
    private $_max_player;

    /**
     * A player marker, either X|O
     *
     * @var string
     */
    private $_min_player;

    /**
     * An instance of GameService
     *
     * @var GameService
     */
    private $_game;

    /**
     * The current game state
     *
     * @var array
     */
    private $_state;

    const MIN_SCORE = -10;
    const MAX_SCORE = 10;
    const TIE_SCORE = 0;

    /**
     * Predicts the next best move
     *
     * @param array             $game_state     The game state
     * @param string            $player         The current player marker X|O
     * @param GameInterface     $game_server    Game server instance
     *
     * @return array The predicted move
     */
    public function predict( $game_state, $player, GameInterface $game_server )
    {

        $this->_state       = $game_state;
        $this->_game        = $game_server;
        $this->_max_player  = $player;
        $this->_min_player  = ( $player == GameConstants::O_MARKER )
            ? GameConstants::X_MARKER
            : GameConstants::O_MARKER;

        return $this->_minMax(
            $this->_state,
            $this->_max_player
        );

    }

    /**
     * A version of the minimax algorithm
     *
     * @param array     $state      The game state
     * @param string    $player     The current player marker X|O
     * @param int       $play       The current play, and index of the game state
     *
     * @return array The predicted move
     */
    private function _minMax( $state, $player, $play = NULL ) {

        if ( $play && $this->_game->isWinningMove( $state, $play ) ) {

            return ( $player == $this->_min_player )
                ? [ 'score' => self::MAX_SCORE, 'move' => $play ]
                : [ 'score' => self::MIN_SCORE, 'move' => $play ];

        }

        if ( $this->_game->isEnd( $state ) ) {

            return [
                'score' => self::TIE_SCORE,
                'move'  => $play
            ];

        }

        $best_move  = NULL;
        $best_score = ( $player == $this->_max_player )
            ? -100000
            : 100000;

        for ( $i = 0; $i < 9; $i++ ) {

            if ( isset( $state[ $i ] ) ) {
                continue;
            }

            $new_state          = $state;
            $new_state[ $i ]    = $player;
            $new_player         = ( $player == $this->_min_player )
                ? $this->_max_player
                : $this->_min_player;

            $response = $this->_minMax( $new_state, $new_player, $i );

            if ( $player == $this->_max_player ) {

                if ( $response['score'] > $best_score ) {
                    $best_score = $response['score'];
                    $best_move  = $i;
                }

            } else {

                if ( $response['score'] < $best_score ) {
                    $best_score = $response['score'];
                    $best_move  = $i;
                }

            }

        }

        return [
            'score' => $best_score,
            'move'  => $best_move
        ];

    }

}