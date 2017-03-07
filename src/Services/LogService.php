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

use Constants\HelperConstants;
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
     * Service and parameter container
     *
     * @var ContainerService
     */
    protected $_container;

    /**
     * Instantiate LogService and inject the service container
     *
     * @param ContainerService $container The service container
     */
    public function __construct( ContainerService $container )
    {

        $this->_container = $container;

    }

    /**
     * Logs an error
     *
     * @param string $message The message to log
     *
     * @return void
     */
    public function log( $message )
    {

        if ( !$this->_log ) {
            $this->_log = ERROR_LOG;
        }

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $functions->errorLog( $message, 3, $this->_log );

    }

    /**
     * Sets the log file path
     *
     * @param string $file The error log file path
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