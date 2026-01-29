<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Notifier_Mail_Smtp
 */
require_once PHPRACK_PATH . '/Adapters/Notifier/Mail/Smtp.php';

class Adapters_Notifier_Mail_SmtpTest extends AbstractTest
{
    #[DataProvider('publicFunctionsProvider')]
    public function testPublicFunctionsWork($a, $b)
    {
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp();
        $result = $adapter->{$a}($b);
        $this->assertTrue(
            $result instanceof phpRack_Adapters_Notifier_Mail_Smtp
        );
    }

    public static function publicFunctionsProvider()
    {
        return array(
            array('setTo', 'test@phprack.com'),
            array('setBody', 'hello, World!'),
            array('setSubject', 'hello, Earth!'),
        );
    }

    public function testSend()
    {
        /**
         * @todo #32 we need to register a valid SMTP account with GOOGLE MAIL
         *  and configure it here, to enable real-life unit testing of the
         *  functionality.
         */
        $a = array(
            'tls'      => true,
            'host'     => 'smtp.gmail.com',
            'port'     => 465,
            'username' => 'yourlogin',
            'password' => 'yourpwd',
        );
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp($a);
        $adapter->setTo('test@phprack.com');
        $adapter->setBody('Passed');
        $adapter->setSubject('Unit Test');

        try {
            $this->assertTrue($adapter->send(), 'send returned false');
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     */
    public function testSendWithoutToException(): void
    {
        $this->expectException(phpRack_Exception::class);
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp();
        $adapter->setBody('Test');
        $adapter->send();
    }

    /**
     */
    public function testSendWithoutBodyException(): void
    {
        $this->expectException(phpRack_Exception::class);
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp();
        $adapter->setTo('test@phprack.com');
        $adapter->send();
    }
}
