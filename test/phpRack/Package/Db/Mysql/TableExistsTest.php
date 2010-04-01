<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Package_Db_Mysql_AbstractTest
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Db/Mysql/AbstractTest.php';

class phpRack_Package_Db_Mysql_TableExistsTest extends phpRack_Package_Db_Mysql_AbstractTest
{
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

    /**
     * @expectedException Exception
     */
    public function testTableExistsWithoutConnect()
    {
        $this->_package->tableExists(self::VALID_TABLE);
    }

    /**
     * @expectedException Exception
     */
    public function testTableExistsWithoutDbExists()
    {
        $this->_getPackageWithValidConnect()
            ->tableExists(self::INVALID_TABLE);
    }
}
