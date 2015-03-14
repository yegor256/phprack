<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Config
 */
require_once PHPRACK_PATH . '/Adapters/Config.php';

class Adapters_ConfigTest extends AbstractTest
{
    private $_config;

    protected function setUp()
    {
        $data = array(
            'configKey' => 'configValue',
            'configKey2' => array(
                'subKey1' => 'value1',
                'subKey2' => 'value2'
            )
        );
        $this->_config = new phpRack_Adapters_Config($data);
    }

    public function testConfig()
    {
        $this->assertTrue(isset($this->_config->configKey));
        $this->assertTrue($this->_config->configKey == 'configValue');
        $this->assertTrue(isset($this->_config->configKey2->subKey1));
        $this->assertTrue($this->_config->configKey2->subKey1 == 'value1');
    }

    public function testIssetWithNotExistingKey()
    {
        $this->assertFalse(isset($this->_config->notExistingKey));
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testGetWithNotExistingKey()
    {
       $this->_config->notExistingKey;
    }
}
