<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

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
     */
    public function testShowServerInfoWithoutConnect(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_adapter->showServerInfo();
    }

    /**
     */
    public function testSchemaWithoutConnect(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_adapter->showSchema();
    }

    /**
     */
    public function testSchemaWithoutDatabaseSelect(): void
    {
        $this->expectException(phpRack_Exception::class);
        try {
            $this->_adapter->connect('jdbc:mysql://localhost');
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $this->_adapter->showSchema();
    }
}
