<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Db_Mysql
 */
require_once PHPRACK_PATH . '/Adapters/Db/Mysql.php';

abstract class phpRack_Adapters_Db_Mysql_AbstractTest extends AbstractTest
{
    /**
     * MySQL adapter
     *
     * @var phpRack_Adapters_Db_Mysql
     */
    protected $_adapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_adapter = new phpRack_Adapters_Db_Mysql();
    }

    protected function tearDown(): void
    {
        unset($this->_adapter);
        parent::tearDown();
    }

}
