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
 * The interface for searching for the Slack API
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface SearchInterface
{

    /**
     * Performs a message search against the SLACK API
     *
     * @param string    $channel    The channel name to search
     * @param string    $text       The text to search for
     * @param int       $limit      The result set limit
     *
     * @return array The result set
     */
    public function find( $channel, $text, $limit = 20 );

}