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

    public function testShowDirectoryWorks()
    {
        $this->assert->disc->showDirectory(
            '.',
            array(
                'exclude' => array(
                    '/\.svn\//'
                ),
            )
        );
    }

}