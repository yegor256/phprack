<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Url
 */
require_once PHPRACK_PATH . '/Adapters/Pear.php';

class Adapters_PearTest extends AbstractTest
{
    /**
     * MySQL adapter
     *
     * @var phpRack_Adapters_Pear
     */
    private $_adapter;

    protected function setUp()
    {
        parent::setUp();
        $this->_adapter = new phpRack_Adapters_Pear();
    }

    protected function tearDown()
    {
        unset($this->_adapter);
        parent::tearDown();
    }

    public function isPackageExists()
    {
        try {
            $this->_adapter->isPackageExists('PEAR');
        } catch (Exception $e) {
            $this->markTestIncomplete($e->getMessage());
        }
    }

    public function testGetPackageName()
    {
        try {
            $this->_adapter->isPackageExists('PEAR');
            $this->_adapter->getPackageName();
        } catch (Exception $e) {
            $this->markTestIncomplete($e->getMessage());
        }
    }

    /**
     * @expectedException Exception
     */
    public function testGetPackageNameWithoutIsPackageExists()
    {
        $this->_adapter->getPackageName();
    }

    public function testGetPackageVersion()
    {
        try {
            $this->_adapter->isPackageExists('PEAR');
            $this->_adapter->getPackageVersion();
        } catch (Exception $e) {
            $this->markTestIncomplete($e->getMessage());
        }
    }

    /**
     * @expectedException Exception
     */
    public function testGetPackageVersionWithoutIsPackageExists()
    {
        $this->_adapter->getPackageVersion();
    }
}
