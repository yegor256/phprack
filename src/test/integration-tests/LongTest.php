<?php
/**
 * AAAAA
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class LongTest extends phpRack_Test
{

    public function testConnectionMonitorAndTestStop()
    {
         /**
         * @see phpRack_Adapters_ConnectionManager
         */
        require_once PHPRACK_PATH . '/Adapters/ConnectionMonitor.php';

        for ($i = 0; $i < 20; $i++) {
            sleep(1);
            phpRack_Adapters_ConnectionMonitor::getInstance()->ping();
        }

        $this->assert
            ->isTrue(true)
            ->onSuccess('Long test: always true');
    }

}
