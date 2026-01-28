<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class phpRack_Package_Php_FnExistsTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Php
     */
    private $_package;

    /**
     * @var phpRack_Result
     */
    private $_result;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_package = $this->_test->assert->php;
        $this->_result = $this->_test->assert->getResult();
    }

    /**
     * @dataProvider providerFunctionNames
     */
    public function testAtLeast($name, $exists)
    {
        $this->_package->fnExists($name);
        if (!is_null($exists)) {
            $this->assertEquals(
                $exists,
                $this->_result->wasSuccessful(),
                "Function '{$name}' returned invalid result"
            );
        }
    }

    public function providerFunctionNames()
    {
        return array(
            array('lcfirst', null),
            array('some_function_that_is_absent', false),
            array('invalid name', false),
            array('printf', true)
        );
    }
}
