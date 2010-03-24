<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Db_Mysql
 */
require_once PHPRACK_PATH . '/Adapters/Db/Mysql.php';

class Adapters_Db_MysqlTest extends AbstractTest
{

    public function testWeCanConnectToDb()
    {
        try {
            $adapter = new phpRack_Adapters_Db_Mysql();
            $adapter->connect('jdbc:mysql://localhost:3306/test');
            $adapter->query('SELECT 1');
        } catch (Exception $e) {
            // ignore it
            $this->_log(get_class($e) . ': ' . $e->getMessage());
        }
    }

    public function testInvalidJdbcUrl()
    {
        try {
            $adapter = new phpRack_Adapters_Db_Mysql();
            $adapter->connect('invalidjdbc');
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertContains('parse error', $e->getMessage());
        }
    }
}
