<?php
/**
 * @version $Id: LintTest.php 309 2010-04-07 08:56:34Z yegor256@yahoo.com $
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

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

}
