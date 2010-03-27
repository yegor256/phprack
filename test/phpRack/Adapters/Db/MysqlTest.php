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
    /**
     * MySQL adapter
     * 
     * @var phpRack_Adapters_Db_Mysql
     */
    private $_adapter;

    protected function setUp()
    {
        parent::setUp();
        $this->_adapter = new phpRack_Adapters_Db_Mysql();
    }

    protected function tearDown()
    {
        unset($this->_adapter);
    }

    public function testWeCanConnectToDb()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost:3306/test');
            $this->_adapter->query('SELECT 1');
        } catch (Exception $e) {
            // ignore it
            $this->_log(get_class($e) . ': ' . $e->getMessage());
        }
    }

    public function testConnectWithNotExistedDb()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost:3306/notexists');
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
    }

    public function testInvalidJdbcUrl()
    {
        try {
            $this->_adapter->connect('invalidjdbc');
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertContains('parse error', $e->getMessage());
        }
    }

    public function testQueryWithoutConnect()
    {
        try {
            $this->_adapter->query("SELECT 1");
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertContains('connect()', $e->getMessage());
        }        
    }

    public function testIsDatabaseSelected()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
            $this->assertFalse($this->_adapter->isDatabaseSelected());
            $this->_adapter->query('USE `test`');
            $this->assertTrue($this->_adapter->isDatabaseSelected());
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
            $this->markTestSkipped('Valid MySQL server was not found');
        }
    }

    public function testIsConnected()
    {
        $this->assertFalse($this->_adapter->isConnected());

        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
            $this->assertTrue($this->_adapter->isConnected());
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
            $this->markTestSkipped('Valid MySQL server was not found');
        }
    }
}
