<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class Php_PhpiniTest extends phpRack_Test
{
    public function testPhpiniMemoryLimit()
    {
        $this->assert->php
            ->ini('short_open_tag')
            ->ini('memory_limit')->atLeast('128M')
            ->ini('register_globals', false);
    }
}
