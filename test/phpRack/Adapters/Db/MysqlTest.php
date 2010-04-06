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
        parent::tearDown();
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

    /**
     * @expectedException Exception
     */
    public function testConnectWithNotExistedDb()
    {
        $this->_adapter->connect('jdbc:mysql://localhost:3306/notexists');
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidJdbcUrl()
    {
        $this->_adapter->connect('invalidjdbc');
    }

    /**
     * @expectedException Exception
     */
    public function testQueryWithoutConnect()
    {
        $this->_adapter->query("SELECT 1");
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


    public function testSchema()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost/test');
            $this->_adapter->showSchema();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * @expectedException Exception
     */
    public function testSchemaWithoutConnect()
    {
        $this->_adapter->showSchema();
    }

    /**
     * @expectedException Exception
     */
    public function testSchemaWithoutDatabaseSelect()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $this->_adapter->showSchema();
    }
}
