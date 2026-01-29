<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
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

    protected function setUp(): void
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
     */
    public function testWithNotExistingFile(): void
    {
        $this->expectException(phpRack_Exception::class);
        new phpRack_Adapters_Config_Ini('noexistingfile.ini', 'production');
    }

    /**
     */
    public function testWithNotExistingSection(): void
    {
        $this->expectException(phpRack_Exception::class);
        new phpRack_Adapters_Config_Ini($this->_iniFilename, 'notexistingsection');
    }

    public function testSectionInheritance()
    {
        $config = new phpRack_Adapters_Config_Ini($this->_iniFilename, 'test2');
        $this->assertTrue($config->resources->inheritedParam === 'inheritedValue');
    }
}
