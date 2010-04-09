<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

class phpRack_Package_Php_PearTest extends AbstractTest
{
    /**
     *
     * @var phpRack_Package_Php_Pear
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
        $this->_result = $this->_test->assert->getResult();
        $this->_package = $this->_test->assert->php->pear;
    }

    public function testPackage()
    {
        $this->_package->package('PEAR');
    }

    public function testPackageWithNotExistingPearPackage()
    {
        $this->_package->package('NotExistingPearPackage');
    }

    public function testAtLeast()
    {
        $this->_package->package('PEAR')
            ->atLeast('1.0');
    }

    public function testAtLeastWithVeryHighVersion()
    {
        $this->_package->package('PEAR')
            ->atLeast('999.0');
    }

    /**
     * @expectedException Exception
     */
    public function testAtLeastWithoutPackage()
    {
        $this->_package->atLeast('1.0');
    }
}
