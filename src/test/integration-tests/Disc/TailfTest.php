<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
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
