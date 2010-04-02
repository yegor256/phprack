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
     * @todo #28 I think that we should inject something into $_GET
     *      in order to check how it works. This test now doesn't really
     *      test the functionality of the package.
     */
    public function testLiveTail()
    {
        $fileName = '../test/phpRack/Package/Disc/_files/1000lines.txt';
        $this->assert->disc->file->tailf($fileName, 20, 5);
    }
}
