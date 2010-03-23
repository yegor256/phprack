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

    protected function setUp()
    {
        parent::setUp();
        $this->_result = new phpRack_Result();
        $this->_package = new phpRack_Package_Db_Mysql($this->_result);
    }

    /**
     * There is no way to specify DB details here, since we don't know
     * in what environment unit tests are executed. The best we can do here
     * is to test that invalid parameters provided will correctly lead
     * to un-successfull result of the test.
     */
    public function testTableExists()
    {
        $this->_package->connect('badHost', 'badPort', 'badDbUser', 'badDbPassword')
            ->dbExists('noExistedDb')
            ->tableExists('noExistedTable');

        $this->assertFalse($this->_result->wasSuccessful());
    }
}
