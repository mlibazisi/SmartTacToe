<?php
/*
 * This file is part of the SmartTacToe package.
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

/*
 * This is the Front Controller that handles all requests
 */

define( 'WEB_ROOT', __DIR__ );

require_once WEB_ROOT . '/../config/errors.php';
require_once WEB_ROOT . '/../vendor/autoload.php';

use Services\RequestService;
use Services\ResponseService;
use Symfony\Component\Yaml\Yaml;

$request        = RequestService::instantiateFromGlobals();
$request_path   = $request->getPath();
$path_map       = [];
$routing_file   =  WEB_ROOT . '/../config/routing.yml';
$routes         = Yaml::parse(
    file_get_contents( $routing_file )
);

foreach ( $routes as $route => $route_info ) {

    if ( isset( $route_info['path'] )
    && ( $route_info['path'] == $request_path ) ) {
        $path_map = $route_info;
        break;
    }

}

if ( empty( $path_map['controller'] )
|| empty( $path_map['action'] ) ) {
    throw new Exception( 'Improperly configured routes in : ' . $routing_file );
}

$parameters = (array)Yaml::parse(
    file_get_contents( WEB_ROOT . '/../config/parameters.yml' )
);

require_once WEB_ROOT . '/../config/services.php';

$container = new \Services\ContainerService( $services, $parameters );

try {

    $controller     = $path_map['controller'] . 'Controller';
    $action         = $path_map['action'] . 'Action';
    $controller_obj = new $controller;

    $controller_obj->setContainer( $container );

    $response = call_user_func_array( [
        $controller_obj,
        $action
    ],
        [ $request ]
    );

} catch ( Exception $e ) {

    $response = new ResponseService(
        ResponseService::$status_texts[
            ResponseService::HTTP_INTERNAL_SERVER_ERROR
        ],
        ResponseService::HTTP_INTERNAL_SERVER_ERROR
    );

}

if ( $response instanceof ResponseService ) {
    $response->send();
} else if ( is_string( $response ) ) {
    echo $response;
}
