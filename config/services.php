<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

/**
 * Service configuration
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
$services['log'] = function () {
  return new Services\LogService();
};

$services['request'] = function () {
  return new Services\RequestService();
};

$services['response'] = function () {
  return new Services\ResponseService();
};

$services['http_client'] = function () {
  return new Services\HttpClientService();
};

$services['o_auth'] = function ( $c ) {
  return new Services\OAuthService( $c );
};

$container = new \Services\ContainerService( $services );