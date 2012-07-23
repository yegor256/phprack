<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Package_Network_Url
 */
require_once PHPRACK_PATH . '/Package/Network/Url.php';

class phpRack_Package_Network_UrlTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Db_Mysql
     */
    private $_package;

    /**
     * @var phpRack_Result
     */
    private $_result;

    protected function setUp()
    {
        parent::setUp();
        $this->_result = $this->_test->assert->getResult();
        $this->_package = $this->_test->assert->network->url;
    }

    public function testUrl()
    {
        $this->_package->url('http://www.google.com');
    }

    public function testUrlWithUrlWithoutScheme()
    {
        $this->_package->url('www.google.com');
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testUrlWithInvalidUrl()
    {
        $this->_package->url('http://');
    }

    public function testRegex()
    {
        try {
            $this->_package->url('http://www.google.com/')
                ->regex('/google\.com/');
            $this->assertTrue($this->_result->wasSuccessful());
        }  catch (Exception $e) {
            $this->_log($e);
            $this->markTestIncomplete();
        }
    }

    public function testRegexWithNotRegExpPattern()
    {
        try {
            $this->_package->url('http://www.google.com/')
                ->regex('google.com');
            $this->assertTrue($this->_result->wasSuccessful());
        }  catch (Exception $e) {
            $this->_log($e);
            $this->markTestIncomplete();
        }
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testRegexWithoutUrlCall()
    {
        $this->_package->regex('/google\.com/');
    }

    public function testRegexWithNotExistedPattern()
    {
        try {
            $this->_package->url('http://www.google.com/')
                ->regex('/notexisted\.com/');
            $this->assertFalse($this->_result->wasSuccessful());
        }  catch (Exception $e) {
            $this->_log(get_class($e) . ': ' . $e->getMessage());
            $this->markTestIncomplete();
        }
    }
}
