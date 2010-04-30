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
     * DB connection details on the server provided by HostGator.com
     */
    const HOST = 'localhost';
    const PORT = 3306;
    const USER = 'fazend_phprack';
    const PASSWORD = 'J8k9Lmn6Hg2Fg';
    const NAME = 'fazend_test';

    /**
     * @see http://phprack.com:2082/3rdparty/phpMyAdmin/index.php
     */
    public function testDatabase()
    {
        // we validate that the DB is accessible
        $this->assert->db->mysql
            ->connect(self::HOST, self::PORT, self::USER, self::PASSWORD) // we can connect
            ->dbExists(self::NAME) // DB exists
            ->showSchema() // full schema is visible
            ->tableExists('user') // table "user" exists
            ->query('SELECT * FROM user LIMIT 5'); // query and return result
    }

    /**
     * @see phpRack_Package_Db_Mysql
     */
    public function testDatabaseConnections()
    {
        $this->assert->db->mysql
            ->connect(self::HOST, self::PORT, self::USER, self::PASSWORD) // we can connect
            ->showConnections(); // full list of currently open connections
    }

    /**
     * @see phpRack_Package_Db_Mysql
     */
    public function testDbInfo()
    {
        $this->assert->db->mysql
            ->connect(self::HOST, self::PORT, self::USER, self::PASSWORD) // we connect
            ->showServerInfo(); // full information about MySQL server
    }
}
