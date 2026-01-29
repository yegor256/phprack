<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class phpRack_Package_Php_PearTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Php_Pear
     */
    private $_package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_package = $this->_test->assert->php->pear;
    }

    public function testPackage()
    {
        $result = $this->_package->package('PEAR');
        $this->assertInstanceOf(phpRack_Package_Php_Pear::class, $result, 'package did not return self');
    }

    public function testPackageWithNotExistingPearPackage()
    {
        $result = $this->_package->package('NotExistingPearPackage');
        $this->assertInstanceOf(phpRack_Package_Php_Pear::class, $result, 'package did not return self');
    }

    public function testAtLeast()
    {
        $result = $this->_package->package('PEAR')
            ->atLeast('1.0');
        $this->assertInstanceOf(phpRack_Package_Php_Pear::class, $result, 'atLeast did not return self');
    }

    public function testAtLeastWithVeryHighVersion()
    {
        $result = $this->_package->package('PEAR')
            ->atLeast('999.0');
        $this->assertInstanceOf(phpRack_Package_Php_Pear::class, $result, 'atLeast did not return self');
    }

    public function testAtLeastWithoutPackage()
    {
        $result = $this->_package->atLeast('1.0');
        $this->assertInstanceOf(phpRack_Package_Php_Pear::class, $result, 'atLeast did not return self');
    }

    public function testShowList()
    {
        $result = $this->_package->showList();
        $this->assertInstanceOf(phpRack_Package_Php_Pear::class, $result, 'showList did not return self');
    }
}
