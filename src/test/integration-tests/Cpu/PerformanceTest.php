<?php
/**
 * AAAAA
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class Cpu_PerformanceTest extends PhpRack_Test
{
    public function testServerIsFast()
    {
        // CPU performance is higher than 3000 BogoMips
        $this->assert->cpu->performance
            ->atLeast(3000);
    }
}
