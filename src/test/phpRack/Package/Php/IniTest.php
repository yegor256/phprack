<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class phpRack_Package_Php_IniTest extends AbstractTest
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

    #[DataProvider('atLeastProvider')]
    public function testAtLeast($value, $assertion)
    {
        $this->_package->ini('memory_limit')->atLeast($value);
        $this->{$assertion}($this->_result->wasSuccessful());
    }

    public static function atLeastProvider()
    {
        $provider = array(
            array('2M', 'assertTrue'),
            array('1', 'assertTrue'),
        );
        if (ini_get('memory_limit') !== '-1') {
            $provider[] = array('1000000K', 'assertFalse');
        }
        $provider[] = array('10Gigabyte', 'assertFalse');
        return $provider;
    }
}
