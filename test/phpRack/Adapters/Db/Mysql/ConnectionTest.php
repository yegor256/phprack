<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Db_Mysql_AbstractTest
 */
require_once PHPRACK_PATH . '/../test/phpRack/Adapters/Db/Mysql/AbstractTest.php';

class phpRack_Adapters_Db_Mysql_ConnectionTest extends phpRack_Adapters_Db_Mysql_AbstractTest
{

    public function testWeCanConnectToDb()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost:3306/test');
            $this->_adapter->query('SELECT 1');
        } catch (Exception $e) {
            // ignore it
            $this->_log($e);
        }
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testConnectWithNotExistedDb()
    {
        $this->_adapter->connect('jdbc:mysql://localhost:3306/notexists');
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testInvalidJdbcUrl()
    {
        $this->_adapter->connect('invalidjdbc');
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testQueryWithoutConnect()
    {
        $this->_adapter->query("SELECT 1");
    }

    public function testIsConnected()
    {
        $this->assertFalse($this->_adapter->isConnected());

        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $this->assertTrue($this->_adapter->isConnected());
    }

    public function testShowConnections()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
            $this->_adapter->showConnections();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testShowConnectionsWithoutConnect()
    {
        $this->_adapter->showConnections();
    }

    public function testIsDatabaseSelected()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $this->assertFalse($this->_adapter->isDatabaseSelected());

        try {
            $this->_adapter->query('USE `test`');
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $this->assertTrue($this->_adapter->isDatabaseSelected());
    }
}
