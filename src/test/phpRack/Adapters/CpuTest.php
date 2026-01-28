<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Cpu
 */
require_once PHPRACK_PATH . '/Adapters/Cpu.php';

class Adapters_CpuTest extends AbstractTest
{
    /**
     * CPU adapter
     *
     * @var phpRack_Adapters_Cpu
     */
    private $_adapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_adapter = phpRack_Adapters_Cpu::factory();
    }

    protected function tearDown(): void
    {
        unset($this->_adapter);
        parent::tearDown();
    }

    public function testGetBogoMips()
    {
        try {
            $bogoMips = $this->_adapter->getBogoMips();
        } catch (Exception $e) {
            $this->_log($e);
            $this->markTestSkipped($e->getMessage());
        }
        $this->assertTrue(is_float($bogoMips));
    }

    public function testGetCpuFrequency()
    {
        try {
            $frequency = $this->_adapter->getCpuFrequency();
        } catch (Exception $e) {
            $this->_log($e);
            $this->markTestSkipped($e->getMessage());
        }
        $this->assertTrue(is_float($frequency));
    }
}
