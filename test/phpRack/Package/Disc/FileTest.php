<?php
/**
 * @version $Id: FileTest.php -1   $
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

require_once dirname(__FILE__) . '/../../../../phpRack/Package/Disc/File.php';

class phpRack_Package_Disc_FileTest extends AbstractTest
{
    private $testFilesDir;

    /**
     *
     * @var phpRack_Package_Disc_File
     */
    private $_package;

    /**
     *
     * @var phpRack_Result
     */
    private $_result;

    protected function setUp()
    {
        parent::setUp();
        $this->_result = new phpRack_Result();
        $this->_package = new phpRack_Package_Disc_File($this->_result);
        $this->testFilesDir = dirname(__FILE__) . '/_files';
    }
    
    public function testCat()
    {
        $fileName = $this->testFilesDir . '/5lines.txt';
        $this->_package->cat($fileName);
        $this->assertTrue($this->_result->wasSuccessful());

        // Check that we receive exactly what we want
        $fileContent = file_get_contents($fileName);
        $this->assertEquals($fileContent, $this->_result->getLog());
    }
    
    public function testTail()
    {
        $linesCountToRetrieve = 100;
        $fileName = $this->testFilesDir . '/1000lines.txt';
        $this->_package->tail($fileName, $linesCountToRetrieve);
        $this->assertTrue($this->_result->wasSuccessful());

        // Check that we receive exactly what we want
        $lines = implode("", array_slice(file($fileName), -$linesCountToRetrieve));
        $this->assertEquals($lines, $this->_result->getLog());
    }

    public function testHead()
    {
        $linesCountToRetrieve = 2;
        $fileName = $this->testFilesDir . '/5lines.txt';
        $this->_package->head($fileName, $linesCountToRetrieve);
        $this->_result->getLog();
        $this->assertTrue($this->_result->wasSuccessful());

        // Check that we receive exactly what we want
        $lines = implode("", array_slice(file($fileName), 0, $linesCountToRetrieve));
        $this->assertEquals($lines, $this->_result->getLog());
    }

    public function testNotExistedFile()
    {
        $fileName = $this->testFilesDir . '/notexists.txt';
        $this->_package->cat($fileName);
        $this->assertFalse($this->_result->wasSuccessful());

        $this->_package->head($fileName, 2);
        $this->assertFalse($this->_result->wasSuccessful());

        $this->_package->tail($fileName, 2);
        $this->assertFalse($this->_result->wasSuccessful());
    }

    public function testCatWithEmptyFile()
    {
        $fileName = $this->testFilesDir . '/empty.txt';
        $this->_package->cat($fileName);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertTrue('' === $this->_result->getLog());
    }

    public function testHeadWithEmptyFile()
    {
        $fileName = $this->testFilesDir . '/empty.txt';
        $this->_package->head($fileName, 10);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertTrue('' === $this->_result->getLog());
    }

    public function testTailWithEmptyFile()
    {
        $fileName = $this->testFilesDir . '/empty.txt';
        $this->_package->tail($fileName, 10);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertTrue('' === $this->_result->getLog());
    }

    public function testHeadIfWeWantRetreveMoreLinesThanFileContain()
    {
        $fileName = $this->testFilesDir . '/5lines.txt';
        $fileContent = file_get_contents($fileName);

        // We should receive full file content in this case
        $this->_package->head($fileName, 20);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertEquals($fileContent, $this->_result->getLog());
    }

    public function testTailIfWeWantRetreveMoreLinesThanFileContain()
    {
        $fileName = $this->testFilesDir . '/5lines.txt';
        $fileContent = file_get_contents($fileName);

        // We should receive full file content in this case
        $this->_package->tail($fileName, 20);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertEquals($fileContent, $this->_result->getLog());
    }

    public function testRelativePaths()
    {
        $fileName = 'test/phpRack/Package/Disc/_files/5lines.txt';
        $fileNames = array(
            '../' . $fileName,
            './../' . $fileName,
            '/../' . $fileName);

        foreach ($fileNames as $fileName) {
            $this->_package->cat($fileName);
            $this->assertTrue($this->_result->wasSuccessful());

            $this->_package->head($fileName, 2);
            $this->assertTrue($this->_result->wasSuccessful());

            $this->_package->tail($fileName, 2);
            $this->assertTrue($this->_result->wasSuccessful());
        }
    }
}
