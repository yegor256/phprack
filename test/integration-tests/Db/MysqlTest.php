<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class MysqlTest extends phpRack_Test
{
    /**
     * see http://phprack.com:2082/3rdparty/phpMyAdmin/index.php
     */
    public function testDatabase()
    {
        $host     = 'localhost';
        $port     = '3306';
        $userName = 'fazend_phprack';
        $password = 'J8k9Lmn6Hg2Fg';
        $dbName   = 'fazend_test';

        // we validate that the DB is accessible
        $this->assert->db->mysql
            ->connect($host, $port, $userName, $password) // we can connect
            ->dbExists($dbName) // DB exists
            ->tableExists('user') // table "user" exists
            ->query('SELECT * FROM user LIMIT 5'); // query and return result
    }
}
