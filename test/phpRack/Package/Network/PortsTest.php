<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Package_Network_Ports
 */
require_once PHPRACK_PATH . '/Package/Network/Ports.php';

class phpRack_Package_Network_PortsTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Network_Ports
     */
    private $_package;

    /**
     * @var phpRack_Result
     */
    private $_result;

    protected function setUp()
    {
        parent::setUp();
        $this->_result = new phpRack_Result();
        $this->_package = new phpRack_Package_Network_Ports($this->_result);
    }

    public function testIsOpen()
    {
        try {
            $this->_package->isOpen(80, 'google.com');
            $this->assertTrue($this->_result->wasSuccessful());
        } catch (Exception $e) {
            $this->_log(get_class($e) . ': ' . $e->getMessage());
            $this->markTestIncomplete();
        }

        $this->_package->isOpen(9999);
        $this->assertFalse($this->_result->wasSuccessful());
    }
}
