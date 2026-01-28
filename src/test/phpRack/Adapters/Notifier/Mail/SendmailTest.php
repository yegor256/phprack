<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Notifier_Mail_Sendmail
 */
require_once PHPRACK_PATH . '/Adapters/Notifier/Mail/Sendmail.php';

class Adapters_Notifier_Mail_SendmailTest extends AbstractTest
{
    /**
     * Sendmail adapter
     *
     * @var Adapters_Notifier_Mail_Sendmail
     */
    protected $_adapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_adapter = new phpRack_Adapters_Notifier_Mail_Sendmail();
    }

    protected function tearDown(): void
    {
        unset($this->_adapter);
        parent::tearDown();
    }

    #[DataProvider('publicFunctionsProvider')]
    public function testPublicFunctionsWork($a, $b)
    {
        $result = $this->_adapter->{$a}($b);
        $this->assertTrue(
            $result instanceof phpRack_Adapters_Notifier_Mail_Sendmail
        );
    }

    public static function publicFunctionsProvider()
    {
        return array(
            array('setTo', 'test@phprack.com'),
            array('setTo', 'test2@phprack.com'),
            array('setBody', 'hello, World!'),
            array('setSubject', 'hello, Earth!'),
        );
    }

    public function testSend()
    {
        $this->markTestSkipped('doesnt work in Travis and Rultor');
        $this->_adapter->setTo('test@phprack.com');
        $this->_adapter->setBody('This is test');
        $this->assertTrue($this->_adapter->send());
    }

    /**
     */
    public function testSendWithoutToException(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_adapter->setBody('Test');
        $this->_adapter->send();
    }

    /**
     */
    public function testSendWithoutBodyException(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_adapter->setTo('test@phprack.com');
        $this->_adapter->send();
    }
}
