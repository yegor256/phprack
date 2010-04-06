<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Package_Disc_File_AbstractTest
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Disc/File/AbstractTest.php';

class phpRack_Package_Disc_File_HeadTest extends phpRack_Package_Disc_File_AbstractTest
{
    public function testHead()
    {
        $linesCountToRetrieve = 2;
        $fileName = $this->_testFilesDir . '/5lines.txt';
        $this->_package->head($fileName, $linesCountToRetrieve);
        $this->_result->getLog();
        $this->assertTrue($this->_result->wasSuccessful());

        // Check that we receive exactly what we want
        $lines = implode("", array_slice(file($fileName), 0, $linesCountToRetrieve));
        $this->assertEquals($lines, $this->_removeLogLine($this->_result->getLog()));
    }

    public function testHeadWithEmptyFile()
    {
        $fileName = $this->_testFilesDir . '/empty.txt';
        $this->_package->head($fileName, 10);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertTrue('' === $this->_removeLogLine($this->_result->getLog()));
    }

    public function testHeadIfWeTryGetMoreLinesThanFileContain()
    {
        $fileName = $this->_testFilesDir . '/5lines.txt';
        $fileContent = file_get_contents($fileName);

        // We should receive full file content in this case
        $this->_package->head($fileName, 20);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertEquals($fileContent, $this->_removeLogLine($this->_result->getLog()));
    }

    public function testHeadWithNotExistingFile()
    {
        $fileName = $this->_testFilesDir . '/notexists.txt';
        $this->_package->head($fileName, 2);
        $this->assertFalse($this->_result->wasSuccessful());
    }
}
