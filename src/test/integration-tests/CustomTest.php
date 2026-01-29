<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class CustomTest extends phpRack_Test
{

    public function testCustomAssertionsAreValid()
    {
        $this->assert->fail("This test is just failed, always");
    }

}
