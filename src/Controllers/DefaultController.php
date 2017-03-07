<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Controllers;
use Interfaces\RequestInterface as Request;
use Services\ResponseService;

/**
 * Default Controller
 *
 * Handles all requests that don't have
 * a URI path
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class DefaultController extends \Controller
{

    /**
     * Dummy main landing page
     *
     * @param Request $request The HTTP request object
     * @return ResponseService
     */
    public function indexAction( Request $request )
    {

        return $this->jsonResponse( 'Hello World' );

    }

}