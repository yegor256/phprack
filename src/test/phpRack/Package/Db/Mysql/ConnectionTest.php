<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Db_Mysql_TestCase
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Db/Mysql/TestCase.php';

class phpRack_Package_Db_Mysql_ConnectionTest extends phpRack_Package_Db_Mysql_TestCase
{
    public function testConnect()
    {
        $this->_package->connect(
            self::INVALID_HOST,
            self::INVALID_PORT,
            self::INVALID_USERNAME,
            self::INVALID_PASSWORD
        );
        $this->assertFalse($this->_result->wasSuccessful());
    }

    public function testCloseConnection()
    {
        $this->_package->closeConnection();
        $this->assertTrue(true, 'closeConnection failed');
    }
}
