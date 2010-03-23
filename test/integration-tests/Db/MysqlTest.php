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
     * @todo #6: I don't know from where take proper values for it
     */
    public function testDatabase()
    {
        $host = 'test';
        $port = 'test';
        $userName = 'test';
        $password = 'test';
        $dbName = 'test';

        // we validate that the DB is accessible
        $this->assert->db->mysql
            ->connect($host, $port, $userName, $password) // we can connect
            ->dbExists($dbName) // DB exists
            ->tableExists('user'); // table "user" exists
    }
}
