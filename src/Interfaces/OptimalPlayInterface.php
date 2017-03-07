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

/**
 * The interface an optimal play predictive algorithm
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface OptimalPlayInterface
{

    /**
     * Predicts the next best move
     *
     * @param array             $game_state     The game state
     * @param string            $player         The current player marker X|O
     * @param GameInterface     $game_server    Game server instance
     *
     * @return array The predicted move
     */
    public function predict( $game_state, $player, GameInterface $game_server );

}