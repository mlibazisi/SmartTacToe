<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

/**
 * Error handler configuration
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
define( 'ERROR_LOG', WEB_ROOT . '/../temp/logs/errors.log' );

error_reporting( E_ALL );
ini_set('display_errors', 0 );
ini_set('log_errors', 1 );
ini_set('error_log',  ERROR_LOG );

set_error_handler( 'simple_error_handler' );
set_exception_handler( 'simple_exception_handler' );
register_shutdown_function( 'simple_shutdown_handler' );

function simple_exception_handler( \Exception $exception )
{

  error_log( "[EXCEPTION] File: {$exception->getFile()} | Line: {$exception->getLine()} | Message: {$exception->getMessage()}\n", 3, ERROR_LOG);

}

function simple_error_handler( $error_level, $error_message, $error_file, $error_line, $error_context )
{

  error_log( "[ERROR] File: $error_file | Line: $error_line | Message: $error_message\n", 3, ERROR_LOG);

}

function simple_shutdown_handler()
{

  $error = error_get_last();

  if ( !empty( $error['message'] ) ) {
    $message = "[SHUTDOWN] File: {$error['file']} | Line: {$error['line']} | Message: {$error['message']}\n";
    error_log( $message, 3, ERROR_LOG);
  }

}
