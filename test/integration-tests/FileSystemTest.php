<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class FileSystemTest extends phpRack_Test
{

    public function testWeHaveEnoughFreeSpace()
    {
        $this->assert->disc->freeSpace
            ->atLeast(100);
    }

}