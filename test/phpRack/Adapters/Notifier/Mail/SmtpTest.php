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
     * @dataProvider testPublicFuncProvider
     */
    public function testPublicFunc($a, $b)
    {
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp(array('class' => 'smtp'));
        $result = $adapter->{$a}($b);
        $this->assertTrue(
            $result instanceof phpRack_Adapters_Notifier_Mail_Smtp
        );
    }

    public function testPublicFuncProvider()
    {
        return array(
            array('setTo', 'test1@example.com'),
            array('setBody', 'helloWorld'),
            array('setSubject', 'helloEarth'),
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
            'class'    => 'smtp',
            'tls'      => true,
            'host'     => 'smtp.gmail.com',
            'port'     => 465,
            'username' => 'yourlogin',
            'password' => 'yourpwd',
        );
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp($a);
        $adapter->setTo('test5@example.com');
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
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp(array('class' => 'smtp'));
        $adapter->setBody('Test');
        $adapter->send();
    }

    /**
     * @expectedException Exception
     */
    public function testSendWithoutBodyException()
    {
        $adapter = new phpRack_Adapters_Notifier_Mail_Smtp(array('class' => 'smtp'));
        $adapter->setTo('test6@example.com');
        $adapter->send();
    }
}
