<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class ShellTest extends phpRack_Test
{

    public function testWhoAmI()
    {
        $this->assert->shell->exec('whoami');
    }

}
