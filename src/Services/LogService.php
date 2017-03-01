<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Services;

use Interfaces\LogInterface;

/**
 * A wrapper for PHP error_log
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class LogService implements LogInterface
{

    /**
     * The log file
     *
     * @var string
     */
    protected $_log;

    /**
     * The log file
     *
     * @var string
     */
    const DEFAULT_LOG_FILE = 'temp/logs/errors.log';

    /**
     * Logs an error
     *
     * @return void
     */
    public function log( $message )
    {

        if ( !$this->_log ) {
            $this->_log = WEB_ROOT . '/../' . self::DEFAULT_LOG_FILE;
        }

        error_log( $message . "\n", 3, $this->_log );

    }

    /**
     * Sets the log file path
     *
     * @return void
     */
    public function setFile( $file )
    {

        $this->_log = $file;

    }

    /**
     * Gets the log file path
     *
     * @return string
     */
    public function getFile()
    {

        return $this->_log;

    }

}