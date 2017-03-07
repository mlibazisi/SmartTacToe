<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Tests\Services;

use Constants\HelperConstants;

class HelperConstantsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testFunctionHelperConstant()
    {

        $this->assertEquals( 'function_helper', HelperConstants::HELPER_FUNCTIONS );

    }

}