<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class FileSystemTest extends PhpRack_Test
{

    public function testWeHaveEnoughFreeSpace()
    {
        $this->assert->disc->freeSpace
            ->atLeast(100);
    }

}