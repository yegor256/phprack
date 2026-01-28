<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage suites
 */

/**
 * @see phpRack_Suite_Test
 */
require_once PHPRACK_PATH . '/Suite/Test.php';

/**
 * View phpinfo().
 *
 * @package Tests
 * @subpackage suites
 */
class phpRack_Suite_Php5_PhpinfoTest extends phpRack_Suite_Test
{
    /**
     * Show phpinfo()
     *
     * @return void
     */
    public function testShowPhpinfo()
    {
        $this->assert->php->phpinfo();
    }
}
