<?php
/**
 * AAAAA
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class phpRack_Package_SimpleTest extends AbstractTest
{

    /**
     *
     * @var phpRack_Package_Php
     */
    private $_package;

    /**
     *
     * @var phpRack_Result
     */
    private $_result;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_result = $this->_test->assert->getResult();
        $this->_package = $this->_test->assert->simple;
    }

    /**
     * Tests direct success function.
     */
    public function testSuccess()
    {
        $this->_package->success("Successful test");
        $this->assertTrue($this->_result->wasSuccessful());
    }

    /**
     * Tests direct fail function.
     */
    public function testFailure()
    {
        $this->_package->fail("Failed test");
        $this->assertFalse($this->_result->wasSuccessful());
    }

}
