<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class PearTest extends phpRack_Test
{
    public function testPearPackages()
    {
        $this->assert->php->pear
            ->package('HTTP_Client') // package exists
            ->atLeast('1.2.1') // at least this version is present
            ->package('PEAR'); // just existence to check
    }

    public function testShowPearPackages()
    {
        $this->assert->php->pear
            ->showList(); // show full list of installed packages
    }
}
