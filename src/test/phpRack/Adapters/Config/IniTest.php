<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Config_Ini
 */
require_once PHPRACK_PATH . '/Adapters/Config/Ini.php';

class Adapters_Config_IniTest extends AbstractTest
{
    private $_iniFilename;

    protected function setUp()
    {
        $this->_iniFilename = dirname(__FILE__) . '/_files/app.ini';
    }

    public function testConfigIni()
    {
        $config = new phpRack_Adapters_Config_Ini($this->_iniFilename, 'production');
        $this->assertTrue(
            $config->resources->db->params->username === 'productionUsernameValue'
        );
    }

    public function testGetAllSections()
    {
        $config = new phpRack_Adapters_Config_Ini($this->_iniFilename);
        $this->assertTrue(
            $config->production->resources->db->params->username === 'productionUsernameValue'
        );
        $this->assertTrue(
            $config->test->resources->db->params->username === 'testUsernameValue'
        );
        $this->assertTrue(
            $config->test2->resources->db->params->username === 'test2UsernameValue'
        );
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testWithNotExistingFile()
    {
        new phpRack_Adapters_Config_Ini('noexistingfile.ini', 'production');
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testWithNotExistingSection()
    {
        new phpRack_Adapters_Config_Ini($this->_iniFilename, 'notexistingsection');
    }

    public function testSectionInheritance()
    {
        $config = new phpRack_Adapters_Config_Ini($this->_iniFilename, 'test2');
        $this->assertTrue($config->resources->inheritedParam === 'inheritedValue');
    }
}
