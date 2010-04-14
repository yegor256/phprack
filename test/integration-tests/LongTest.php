<?php
/**
 * @version $Id$
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

        for ($i = 0; $i < 100; $i++) {
            sleep(1);
            phpRack_Adapters_ConnectionMonitor::getInstance()->ping();
        }
    }

}