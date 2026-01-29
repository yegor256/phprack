<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class Disc_TailfTest extends phpRack_Test
{
    public function testLiveTail()
    {
        $fileName = '../test/phpRack/Package/Disc/_files/1000lines.txt';
        $this->assert->disc->file->tailf($fileName, 20, 5);
    }
}
