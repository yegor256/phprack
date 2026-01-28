<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class Php_PhpinfoTest extends phpRack_Test
{

    public function testPhpinfoIsVisible()
    {
        $this->assert->php->phpinfo();
    }

}
