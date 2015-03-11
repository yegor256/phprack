<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Shell
 */
require_once PHPRACK_PATH . '/Package/Shell.php';

class phpRack_Package_ShellTest extends AbstractTest
{

    /**
     * @var phpRack_Package_Shell
     */
    private $_package;

    protected function setUp()
    {
        parent::setUp();
        $this->_package = $this->_test->assert->shell;
    }

    public function testBasicRequestWorks()
    {
        $this->_package->exec('who am i');

    }

    /**
     * Tests for shell output text.
     */
    public function testRequestOutput()
    {
        $this->_package->exec('dir', '/test/');

    }

}
