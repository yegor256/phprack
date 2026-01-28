<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class phpRack_Package_Php_IniTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Php
     */
    private $_package;

    /**
     * @var phpRack_Result
     */
    private $_result;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_package = $this->_test->assert->php;
        $this->_result = $this->_test->assert->getResult();
    }

    /**
     * @dataProvider testAtLeastProvider
     */
    public function testAtLeast($data)
    {
        $this->_package->ini('memory_limit')->atLeast($data[0]);
        $this->{$data[1]}($this->_result->wasSuccessful());
    }

    public function testAtLeastProvider()
    {
        return array(
            array(array('2M', 'assertTrue')), // 2 megabyte
            array(array('1', 'assertTrue')), // 1 byte
            array(array('1000000K', 'assertFalse')), // about 1G
            array(array('10Gigabyte', 'assertFalse')) // wrong foramt
        );
    }
}
