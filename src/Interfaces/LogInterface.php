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
 * The interface for error log wrappers
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface LogInterface
{

    /**
     * Logs an error
     *
     * @param string $message The message to log
     *
     * @return void
     */
    public function log( $message );

    /**
     * Sets the log file path
     *
     * @param string $file The error log file path
     *
     * @return void
     */
    public function setFile( $file );

    /**
     * Gets the log file path
     *
     * @return string
     */
    public function getFile();

}