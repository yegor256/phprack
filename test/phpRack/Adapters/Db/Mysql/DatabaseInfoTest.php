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

class phpRack_Adapters_Db_Mysql_DatabaseInfoTest extends phpRack_Adapters_Db_Mysql_AbstractTest
{

    public function testSchema()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost/test');
            $this->_adapter->showSchema();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public function testShowServerInfo()
    {
        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
            $this->_adapter->showServerInfo();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testShowServerInfoWithoutConnect()
    {
        $this->_adapter->showServerInfo();
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testSchemaWithoutConnect()
    {
        $this->_adapter->showSchema();
    }

    /**
     * @expectedException phpRack_Exception
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
