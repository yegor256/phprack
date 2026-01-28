<?php
/**
 * AAAAA
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Db_Mysql_AbstractTest
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Db/Mysql/AbstractTest.php';

class phpRack_Package_Db_Mysql_ConnectionTest extends phpRack_Package_Db_Mysql_AbstractTest
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
    }
}
