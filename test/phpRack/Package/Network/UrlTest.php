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
        $this->_result = new phpRack_Result();
        $this->_package = new phpRack_Package_Network_Url($this->_result);
    }

    public function testUrl()
    {
        $this->_package->url('http://www.google.com');
    }

    public function testUrlWithUrlWithoutScheme()
    {
        $this->_package->url('www.google.com');
    }

    public function testUrlWithInvalidUrl()
    {
        try {
            $this->_package->url('http://');
            $this->fail('An expected exception has not been raised.');
        }  catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
    }

    public function testRegex()
    {
        try {
            $this->_package->url('http://www.google.com/')
                ->regex('/google\.com/');
            $this->assertTrue($this->_result->wasSuccessful());
        }  catch (Exception $e) {
            $this->_log(get_class($e) . ': ' . $e->getMessage());
            $this->markTestIncomplete();
        }
    }

    public function testRegexWithoutUrlCall()
    {
        try {
            $this->_package->regex('/google\.com/');
            $this->fail('An expected exception has not been raised.');
        }  catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
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
