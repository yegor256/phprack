<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class FileTest extends phpRack_Test
{
    public function testFileContent()
    {
        $fileName = '../test/phpRack/Package/Disc/_files/5lines.txt';
        $this->assert->disc->file->cat($fileName);
        $this->assert->disc->file->head($fileName, 2);
        $this->assert->disc->file->tail($fileName, 2);
    }
}
