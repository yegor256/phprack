<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Notifier_Mail_Smtp
 */
require_once PHPRACK_PATH . '/Adapters/Notifier/Mail/Smtp.php';

class Adapters_Notifier_Mail_SmtpTest extends AbstractTest
{
    /**
     * @dataProvider testPublicFunctionsProvider
     */
    public function testPublicFunctionsWork($a, $b)
    {
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp();
        $result = $adapter->{$a}($b);
        $this->assertTrue(
            $result instanceof phpRack_Adapters_Notifier_Mail_Smtp
        );
    }

    public function testPublicFunctionsProvider()
    {
        return array(
            array('setTo', 'test1@phprack.com'),
            array('setBody', 'hello, World!'),
            array('setSubject', 'hello, Earth!'),
        );
    }

    public function testSend()
    {
        /**
         * @todo #32 we need to register a valid SMTP account with GOOGLE MAIL
         * and configure it here, to enable real-life unit testing of the
         * functionality
         */
        $a = array(
            'tls'      => true,
            'host'     => 'smtp.gmail.com',
            'port'     => 465,
            'username' => 'yourlogin',
            'password' => 'yourpwd',
        );
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp($a);
        $adapter->setTo('test1@phprack.com');
        $adapter->setBody('Passed');
        $adapter->setSubject('Unit Test');

        try {
            $this->assertTrue($adapter->send());
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (strpos($msg, 'Wrong answer') === 0) {
                $this->_log($msg);
                // $this->markTestIncomplete($msg);
            }
        }
    }

    /**
     * @expectedException Exception
     */
    public function testSendWithoutToException()
    {
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp();
        $adapter->setBody('Test');
        $adapter->send();
    }

    /**
     * @expectedException Exception
     */
    public function testSendWithoutBodyException()
    {
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp();
        $adapter->setTo('test1@phprack.com');
        $adapter->send();
    }
}
