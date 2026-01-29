<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

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

    protected function setUp(): void
    {
        parent::setUp();
        $this->_result = $this->_test->assert->getResult();
        $this->_package = $this->_test->assert->network->url;
    }

    public function testUrl()
    {
        $result = $this->_package->url('http://www.google.com');
        $this->assertInstanceOf(phpRack_Package_Network_Url::class, $result, 'url did not return self');
    }

    public function testUrlWithUrlWithoutScheme()
    {
        $result = $this->_package->url('www.google.com');
        $this->assertInstanceOf(phpRack_Package_Network_Url::class, $result, 'url did not return self');
    }

    /**
     */
    public function testUrlWithInvalidUrl(): void
    {
        $this->expectException(phpRack_Exception::class);
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
     */
    public function testRegexWithoutUrlCall(): void
    {
        $this->expectException(phpRack_Exception::class);
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

    public function testValidResponseCode()
    {
        $this->_package->url('http://www.google.com/')
        ->responseCode('/^[2|3]/');
        $this->assertTrue($this->_result->wasSuccessful());
    }

    public function testInvalidResponseCode()
    {
        $this->_package->url('http://www.google.com/some_wrong_link')
            ->responseCode('/^[2|3]/');
        $this->assertFalse($this->_result->wasSuccessful());
    }
}
