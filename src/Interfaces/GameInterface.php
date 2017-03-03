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

/**
 * The game server interface
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
interface GameInterface
{

    /**
     * Gets help options to the user
     *
     * @return  array
     */
    public function help();

}