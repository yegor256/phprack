<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Disc_File
 */
require_once PHPRACK_PATH . '/Package/Disc/File.php';

abstract class phpRack_Package_Disc_File_AbstractTest extends AbstractTest
{
    /**
     * Directory where we have sample files, will be set it in setUp() function
     * @var string
     */
    protected $_testFilesDir;

    /**
     * We will store here temp file name, which should be removed after test end
     * @var string
     */
    protected $_tmpFileName;

    /**
     * @var phpRack_Package_Disc_File
     */
    protected $_package;

    /**
     * @var phpRack_Result
     */
    protected $_result;

    protected function setUp()
    {
        parent::setUp();
        $this->_result = $this->_test->assert->getResult();
        $this->_package = $this->_test->assert->disc->file;
        $this->_testFilesDir = PHPRACK_PATH . '/../test/phpRack/Package/Disc/_files';
    }

    protected function tearDown()
    {
        clearstatcache();
        // Remove tmp file
        if ($this->_tmpFileName && file_exists($this->_tmpFileName)) {
            chmod($this->_tmpFileName, 0600); // Reset file permissions, for avoid permission denied on Windows
            unlink($this->_tmpFileName);
        }
        parent::tearDown();
    }

    protected function _removeLogLine($fileContent)
    {
        $pos = strpos($fileContent, "\n");
        // If we have only log line
        if ($pos === false || strlen($fileContent) == $pos + 1) {
            return '';
        }
        return substr($fileContent, $pos + 1);
    }
}
