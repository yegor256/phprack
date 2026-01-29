<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class phpRack_Package_Cpu_PerformanceTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Cpu_Performance
     */
    private $_package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_package = $this->_test->assert->cpu->performance;
    }

    public function testAtLeast()
    {
        $result = $this->_package->atLeast(1.0);
        $this->assertInstanceOf(phpRack_Package_Cpu_Performance::class, $result, 'atLeast did not return self');
    }

    public function testAtLeastWithVeryBigValue()
    {
        $result = $this->_package->atLeast(1000000000.0);
        $this->assertInstanceOf(phpRack_Package_Cpu_Performance::class, $result, 'atLeast did not return self');
    }
}
