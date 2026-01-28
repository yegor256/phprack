<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class LongWaitTest extends phpRack_Test
{
    public function testLongWait()
    {
        // simulate some long processing
        sleep(10);

        $this->assert
            ->isTrue(true)
            ->onSuccess('Long wait test: always true');
    }

}
