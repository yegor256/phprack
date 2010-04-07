<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class TailfTest extends phpRack_Test
{
    /**
     * Test with offset added in unit tests
     * {@see phpRack_Package_Disc_File_TailfTest::testTailfWithOffset()}
     */
    public function testLiveTail()
    {
        $fileName = '../test/phpRack/Package/Disc/_files/1000lines.txt';
        $this->assert->disc->file->tailf($fileName, 20, 5);
    }
}
