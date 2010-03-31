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

class phpRack_Package_Db_MysqlTest extends AbstractTest
{
    /**
     *
     * @var phpRack_Package_Db_Mysql
     */
    private $_package;

    /**
     *
     * @var phpRack_Result
     */
    private $_result;

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
        $this->_result = new phpRack_Result();
        $this->_package = new phpRack_Package_Db_Mysql($this->_result);
    }

    protected function tearDown()
    {
        unset($this->_package);
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

    public function testDbExists()
    {
        try {
            $this->_getPackageWithValidConnect()
                ->dbExists(self::INVALID_DATABASE);
            $this->assertFalse($this->_result->wasSuccessful());
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
            $this->markTestSkipped('Valid MySQL server was not found');
        }
    }

    public function testDbExistsWithoutConnect()
    {
        try {
            $this->_package->dbExists(self::VALID_DATABASE);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertContains('connect()', $e->getMessage());
        }
    }

    public function testTableExists()
    {
        try {
            $this->_getPackageWithValidConnect()
                ->dbExists(self::VALID_DATABASE)
                ->tableExists(self::INVALID_TABLE);
            $this->assertFalse($this->_result->wasSuccessful());
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
            $this->markTestSkipped('Valid MySQL database was not found');
        }
    }

    public function testTableExistsWithoutConnect()
    {
        try {
            $this->_package->tableExists(self::VALID_TABLE);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertContains('connect()', $e->getMessage());
        }
    }

    public function testTableExistsWithoutDbExists()
    {
        try {
            $this->_getPackageWithValidConnect()
                ->tableExists(self::INVALID_TABLE);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
    }

    public function testQuery()
    {
        try {
            $this->_getPackageWithValidConnect()
                ->dbExists(self::VALID_DATABASE)
                ->query('SELECT 1');
            $this->assertTrue($this->_result->wasSuccessful());
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
            $this->markTestSkipped('Valid MySQL database was not found');
        }
    }

    public function testQueryWithInvalidQuery()
    {
        try {
            $this->_getPackageWithValidConnect()
                ->dbExists(self::VALID_DATABASE)
                ->query('NOTEXISTEDFUNCTION 1');
            $this->assertTrue($this->_result->wasSuccessful());
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
            $this->markTestSkipped('Valid MySQL database was not found');
        }
    }

    /**
     * @expectedException Exception
     */
    public function testQueryWithoutConnect()
    {
        $this->_package->query('SELECT 1');
    }

    public function testCloseConnection()
    {
        $this->_package->closeConnection();
    }
}
