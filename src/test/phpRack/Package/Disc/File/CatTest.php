<?php
/**
 * AAAAA
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Disc_File_AbstractTest
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Disc/File/AbstractTest.php';

class phpRack_Package_Disc_File_CatTest extends phpRack_Package_Disc_File_AbstractTest
{
    public function testCat()
    {
        $fileName = $this->_testFilesDir . '/5lines.txt';
        $this->_package->cat($fileName);
        $this->assertTrue($this->_result->wasSuccessful());

        // Check that we receive exactly what we want
        $fileContent = file_get_contents($fileName);
        $this->assertEquals($fileContent, $this->_removeLogLine($this->_result->getLog()));
    }

    public function testCatWithEmptyFile()
    {
        $fileName = $this->_testFilesDir . '/empty.txt';
        $this->_package->cat($fileName);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertTrue('' === $this->_removeLogLine($this->_result->getLog()));
    }

    public function testCatWithNotExistingFile()
    {
        $fileName = $this->_testFilesDir . '/notexists.txt';
        $this->_package->cat($fileName);
        $this->assertFalse($this->_result->wasSuccessful());
    }
}
