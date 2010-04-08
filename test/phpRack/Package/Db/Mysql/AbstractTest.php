<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Package_Db_Mysql
 */
require_once PHPRACK_PATH . '/Package/Db/Mysql.php';

abstract class phpRack_Package_Db_Mysql_AbstractTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Db_Mysql
     * @see setUp()
     */
    protected $_package;

    /**
     * @var phpRack_Result
     * @see setUp()
     */
    protected $_result;

    const INVALID_HOST = 'invalidHost';
    const INVALID_PORT = 0;
    const INVALID_USERNAME = 'invalidUsername';
    const INVALID_PASSWORD = 'invalidPassword';
    const INVALID_DATABASE = 'invalidDatabase';
    const INVALID_TABLE = 'invalidTable';

    // Just for better code coverage, not mandatory to be really valid
    const VALID_HOST = 'localhost';
    const VALID_PORT = 3306;
    const VALID_USERNAME = 'root';
    const VALID_PASSWORD = '';
    const VALID_DATABASE = 'test';
    const VALID_TABLE = 'test';

    protected function setUp()
    {
        parent::setUp();
        $this->_result = $this->_test->assert->getResult();
        $this->_package = $this->_test->assert->db->mysql;
    }

    protected function tearDown()
    {
        unset($this->_package);
        parent::tearDown();
    }

    protected function _getPackageWithValidConnect()
    {
        return $this->_package->connect(
            self::VALID_HOST,
            self::VALID_PORT,
            self::VALID_USERNAME,
            self::VALID_PASSWORD
        );
    }
}
