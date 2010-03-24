<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Package_Php
 */
require_once PHPRACK_PATH . '/Package/Php.php';

class phpRack_Package_Php_LintTest extends AbstractTest
{
    
    /**
    * Directory where we have sample files, will be set it in setUp() function
    */
    private $_testFilesDir;

    /**
     *
     * @var phpRack_Package_Php
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
        $this->_package = new phpRack_Package_Php($this->_result);
        $this->_testFilesDir = dirname(__FILE__) . '/_files';
    }

    public function testLintExcludeOption()
    {
        $options = array(
            'exclude' => array(
                '/corrupt*/',
                '/\.svn/'
            )
        );
        $this->_package->lint($this->_testFilesDir . '/php', $options);
        $this->assertTrue($this->_result->wasSuccessful());
    }

    public function testLintExtensionOption()
    {
        $options = array(
            'extensions' => 'phtml',
        );
        $this->_package->lint($this->_testFilesDir . '/php', $options);
        $this->assertTrue($this->_result->wasSuccessful());
    }

    public function testLintWithCorruptedFile()
    {
        $this->_package->lint($this->_testFilesDir . '/php');
        $this->assertFalse($this->_result->wasSuccessful());
    }

    public function testLintWithNotExistedDirectory()
    {
        $options = array(
            'exclude' => array(
                '/corrupt*/',
                '/\.svn/'
            )
        );
        $this->_package->lint($this->_testFilesDir . '/notexists', $options);
        $this->assertFalse($this->_result->wasSuccessful());
    }
    
}
