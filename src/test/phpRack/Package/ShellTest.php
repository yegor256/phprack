<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Shell
 */
require_once PHPRACK_PATH . '/Package/Shell.php';

class phpRack_Package_ShellTest extends AbstractTest
{

    /**
     * @var phpRack_Package_Shell
     */
    private $_package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_package = $this->_test->assert->shell;
    }

    public function testBasicRequestWorks()
    {
        $result = $this->_package->exec('who am i');
        $this->assertInstanceOf(phpRack_Package_Shell::class, $result, 'exec did not return self');
    }

    /**
     * Tests for shell output text.
     */
    public function testRequestOutput()
    {
        $result = $this->_package->exec('dir', '/test/');
        $this->assertInstanceOf(phpRack_Package_Shell::class, $result, 'exec did not return self');
    }

}
