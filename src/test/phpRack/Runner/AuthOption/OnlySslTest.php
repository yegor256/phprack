<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class Runner_AuthOption_OnlySslTest extends AbstractTest
{
    protected function setUp(): void
    {
        global $phpRackConfig;
        $authOptions = array(
            'auth' => array(
                'username' => uniqid(),
                'password' => uniqid(),
                'onlySSL' => true,
            )
        );
        /**
         * @see phpRack_Runner
         */
        require_once PHPRACK_PATH . '/Runner.php';
        $this->_runner = new phpRack_Runner(
            array_merge($phpRackConfig, $authOptions)
        );
    }

    protected function tearDown(): void
    {
        unset($this->_runner);
    }

    public function testWithHttpsConnection()
    {
        global $_SERVER;
        $_SERVER['HTTPS'] = 'on';
        $this->assertTrue($this->_runner->isEnoughSecurityLevel());
    }

    public function testWithHttpConnection()
    {
        global $_SERVER;
        unset($_SERVER['HTTPS']);
        $this->assertFalse($this->_runner->isEnoughSecurityLevel());
    }
}
