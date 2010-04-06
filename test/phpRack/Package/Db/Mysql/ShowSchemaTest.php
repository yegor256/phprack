<?php
/**
 * @version $Id$
 */

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
     * @expectedException Exception
     */
    public function testShowSchemaWithoutConnect()
    {
        $this->_package->showSchema();
    }

    /**
     * @expectedException Exception
     */
    public function testShowSchemaWithoutDbExists()
    {
        $this->_getPackageWithValidConnect()
            ->showSchema();
    }
}
