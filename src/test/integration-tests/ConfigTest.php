<?php
/**
 * AAAAA
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class ConfigTest extends phpRack_Test
{

    public function testConfigIni()
    {
        /**
         * @see phpRack_Adapters_Config_Ini
         */
        require_once PHPRACK_PATH . '/Adapters/Config/Ini.php';
        $ini = new phpRack_Adapters_Config_Ini(
            PHPRACK_PATH . '/../test/phpRack/Adapters/Config/_files/app.ini',
            'production'
        );
        $this->assert->db->mysql
            ->connect(
                'localhost',
                3306,
                $ini->resources->db->params->username,
                $ini->resources->db->params->password
            )
            ->dbExists($ini->resources->db->params->dbname)
            ->showConnections();
    }

}
