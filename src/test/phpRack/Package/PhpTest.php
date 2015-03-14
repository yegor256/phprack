<?php

require_once 'AbstractTest.php';

require_once PHPRACK_PATH . '/Package/Shell.php';

class phpRack_Package_PhpTest extends AbstractTest
{

    /**
     * @var phpRack_Package_Php
     */
    private $package;

    /**
     *
     * @var phpRack_Result
     */
    private $result;

    protected function setUp()
    {
        parent::setUp();
        $this->package = $this->_test->assert->php;
        $this->result = $this->_test->assert->getResult();
    }

    protected function tearDown()
    {
        parent::tearDown();
        ini_restore('default_socket_timeout');
    }

    /**
     * @covers phpRack_Package_Php::atLeast
     * @covers phpRack_Package_Php::_sizeFormat
     */
    public function testAtLeastIsLowerThanRequired()
    {
        ini_set('default_socket_timeout', 60);
        $this->package->ini('default_socket_timeout')->atLeast(50);
        $this->assertTrue($this->result->wasSuccessful(), 'atleast 50 required and 60 isset');
    }

    /**
     * @covers phpRack_Package_Php::atLeast
     * @covers phpRack_Package_Php::_sizeFormat
     */
    public function testAtLeastIsHigherThanRequired()
    {
        ini_set('default_socket_timeout', 60);
        $this->package->ini('default_socket_timeout')->atLeast(70);
        $this->assertFalse($this->result->wasSuccessful(), 'atleast 70 required but 60 isset');
    }

    /**
     * @covers phpRack_Package_Php::atLeast
     * @covers phpRack_Package_Php::_sizeFormat
     */
    public function testAtLeastIsEqualToRequired()
    {
        ini_set('default_socket_timeout', 60);
        $this->package->ini('default_socket_timeout')->atLeast(60);
        $this->assertTrue($this->result->wasSuccessful(), 'atleast 60 required and 60 isset');
    }

    /**
     * @covers phpRack_Package_Php::atLeast
     */
    public function testIniHasIncorrectNumericFormat()
    {
        ini_set('default_socket_timeout', '60S');
        $this->package->ini('default_socket_timeout')->atLeast('60S');
        $this->assertFalse($this->result->wasSuccessful(), 'php.ini value has incorrect numeric format');
    }

    /**
     * @covers phpRack_Package_Php::atLeast
     * @covers phpRack_Package_Php::_sizeFormat
     */
    public function testIniKiloPrefixMultipliers()
    {
        ini_set('default_socket_timeout', '60K');
        $this->package->ini('default_socket_timeout')->atLeast(60 * phpRack_Package_Php::SIZE_FORMAT);
        $this->assertTrue($this->result->wasSuccessful());
    }

    /**
     * @covers phpRack_Package_Php::atLeast
     * @covers phpRack_Package_Php::_sizeFormat
     */
    public function testIniMegaPrefixMultiplier()
    {
        ini_set('default_socket_timeout', '60M');
        $this->package->ini('default_socket_timeout')->atLeast(60 * phpRack_Package_Php::SIZE_FORMAT * phpRack_Package_Php::SIZE_FORMAT);
        $this->assertTrue($this->result->wasSuccessful());
    }

    /**
     * @covers phpRack_Package_Php::atLeast
     * @covers phpRack_Package_Php::_sizeFormat
     */
    public function testIniGigaPrefixMultiplier()
    {
        ini_set('default_socket_timeout', '60G');
        $this->package->ini('default_socket_timeout')->atLeast(60 * phpRack_Package_Php::SIZE_FORMAT * phpRack_Package_Php::SIZE_FORMAT * phpRack_Package_Php::SIZE_FORMAT);
        $this->assertTrue($this->result->wasSuccessful());
    }

}
