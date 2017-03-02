<?php
/*
 * This file is part of the Slackable package (https://github.com/mlibazisi/slackable)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Interfaces;
use Exceptions\ServiceException;

/**
 * The interface for our OAuth services
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface OAuthInterface
{

    /**
     * Request an access token from an OAuth server
     *
     * @param string $access_url The url to submit the request
     * @return mixed
     * @throws ServiceException
     */
    public function getAccessToken( $access_url );

}