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
     * @todo #6 From where I should take valid access details for our db test?
     */
    public function testTableExists()
    {
        $this->_package->connect('badHost', 'badPort', 'badDbUser', 'badDbPassword')
            ->dbExists('noExistedDb')
            ->tableExists('noExistedTable');

        $this->assertFalse($this->_result->wasSuccessful());
    }
}
