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

class phpRack_Package_Disc_File_TailfTest extends phpRack_Package_Disc_File_AbstractTest
{
    public function testTailf()
    {
        $linesCountToRetrieve = 100;
        $fileName = $this->_testFilesDir . '/1000lines.txt'; 
        $this->_package->tailf($fileName, $linesCountToRetrieve);
        $this->assertTrue($this->_result->wasSuccessful());

        // Check that we receive exactly what we want
        $lines = implode("", array_slice(file($fileName), -$linesCountToRetrieve));
        $this->assertEquals($lines, $this->_removeLogLine($this->_result->getLog()));
    }

    public function testTailfWithEmptyFile()
    {
        $fileName = $this->_testFilesDir . '/empty.txt';
        $this->_package->tailf($fileName, 10);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertTrue('' === $this->_removeLogLine($this->_result->getLog()));
    }

    public function testTailfIfWeTryGetMoreLinesThanFileContain()
    {
        $fileName = $this->_testFilesDir . '/5lines.txt';
        $fileContent = file_get_contents($fileName);

        // We should receive full file content in this case
        $this->_package->tailf($fileName, 20);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertEquals($fileContent, $this->_removeLogLine($this->_result->getLog()));
    }

    public function testTailfWithNotExistingFile()
    {
        $fileName = $this->_testFilesDir . '/notexists.txt';
        $this->_package->tailf($fileName, 2);
        $this->assertFalse($this->_result->wasSuccessful());
    }

    public function testTailfWithOffset()
    {
        $this->_test->setAjaxOptions(array('fileLastOffset' => 0));
        $fileName = $this->_testFilesDir . '/5lines.txt';
        $fileContent = file_get_contents($fileName);
        $this->_package->tailf($fileName, 2, 5);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertEquals($fileContent, $this->_result->getLog());
    }
}
