<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Network_Ports
 */
require_once PHPRACK_PATH . '/Package/Network/Ports.php';

class phpRack_Package_Network_PortsTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Network_Ports
     */
    private $_package;

    /**
     * @var phpRack_Result
     */
    private $_result;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_result = $this->_test->assert->getResult();
        $this->_package = $this->_test->assert->network->ports;
    }

    public function testIsOpen()
    {
        try {
            $this->_package->isOpen(80, 'google.com');
            $this->assertTrue($this->_result->wasSuccessful());
        } catch (Exception $e) {
            $this->_log($e);
            $this->markTestIncomplete();
        }

        $this->_package->isOpen(9999);
        $this->assertFalse($this->_result->wasSuccessful());
    }
}
