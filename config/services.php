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
 * Service Injection Container configuration
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
$services['log'] = function ( $c ) {
  return new Services\LogService( $c );
};

$services['request'] = function () {
  return new Services\RequestService();
};

$services['response'] = function ( $c ) {
  return new Services\ResponseService( $c );
};

$services['http_client'] = function ( $c ) {
  return new Services\HttpClientService( $c );
};

$services['o_auth'] = function ( $c ) {
  return new Services\OAuthService( $c );
};

$services['function_helper'] = function () {
  return new Helpers\FunctionHelper();
};

$services['game_server'] = function ( $c ) {
  return new Services\GameService( $c );
};

$services['optimal_play'] = function () {
  return new Services\OptimalPlayService();
};

$services['search'] = function ( $c ) {
  return new Services\SearchService( $c );
};