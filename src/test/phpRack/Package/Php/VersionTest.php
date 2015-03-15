<?php

require_once 'AbstractTest.php';

class phpRack_Package_Php_VersionTest extends AbstractTest
{

    /**
     * @var phpRack_Package_Php
     */
    private $_package;

    /**
     * @var phpRack_Result
     */
    private $_result;

    public function setUp()
    {
        parent::setUp();
        $this->_package = $this->_test->assert->php;
        $this->_result = $this->_test->assert->getResult();
    }

    /**
     * @covers phpRack_Package_Php_Version::atLeast
     */
    public function testIsOlder()
    {
        $this->_package->version->atLeast('4.2.3');
        $this->assertTrue($this->_result->wasSuccessful(), '5.4 >= 4.2.3');
    }

    /**
     * @covers phpRack_Package_Php_Version::atLeast
     */
    public function testIsEqual()
    {
        $this->_package->version->atLeast(phpversion());
        $this->assertTrue($this->_result->wasSuccessful(), '5.4 == 5.4');
    }

    /**
     * @covers phpRack_Package_Php_Version::atLeast
     */
    public function testIsNewer()
    {
        $this->_package->version->atLeast('9.5.5');
        $this->assertFalse($this->_result->wasSuccessful(), '5.4 < 9.5.5');
    }

}
