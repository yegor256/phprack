<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class PerformanceTest extends phpRack_Test
{
    public function testServerIsFast()
    {
        // CPU performance is higher than 3000 BogoMips
        $this->assert->cpu->performance
            ->atLeast(3000);
    }
}