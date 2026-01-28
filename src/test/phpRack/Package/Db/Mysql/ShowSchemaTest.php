<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Db_Mysql_AbstractTest
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Db/Mysql/AbstractTest.php';

class phpRack_Package_Db_Mysql_ShowSchemaTest extends phpRack_Package_Db_Mysql_AbstractTest
{
    public function testShowSchema()
    {
        try {
            $this->_getPackageWithValidConnect()
                ->dbExists(self::VALID_DATABASE)
                ->showSchema();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
            $this->markTestSkipped('Valid MySQL database was not found');
        }
    }

    /**
     */
    public function testShowSchemaWithoutConnect(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_package->showSchema();
    }

    /**
     */
    public function testShowSchemaWithoutDbExists(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_getPackageWithValidConnect()
            ->showSchema();
    }
}
