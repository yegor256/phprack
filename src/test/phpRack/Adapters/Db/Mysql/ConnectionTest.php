<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

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
     */
    public function testConnectWithNotExistedDb(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_adapter->connect('jdbc:mysql://localhost:3306/notexists');
    }

    /**
     */
    public function testInvalidJdbcUrl(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_adapter->connect('invalidjdbc');
    }

    /**
     */
    public function testQueryWithoutConnect(): void
    {
        $this->expectException(phpRack_Exception::class);
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
     */
    public function testShowConnectionsWithoutConnect(): void
    {
        $this->expectException(phpRack_Exception::class);
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
