<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Mail_Transport_Smtp
 */
require_once PHPRACK_PATH . '/Adapters/Mail/Transport/Smtp.php';

class Adapters_Mail_Abstract_SmtpTest extends AbstractTest
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @dataProvider testPublicFuncProvider
     */
    public function testPublicFunc($a, $b)
    {
        $arr = array('smtp');
        $adapter = new phpRack_Adapters_Mail_Transport_Smtp($arr);
        $result = $adapter->{$a}($b);
        $this->assertTrue(
            $result instanceof phpRack_Adapters_Mail_Transport_Smtp
        );
    }

    public function testPublicFuncProvider()
    {
        return array(
            array('setTo', 'ww@ww.ru'),
            array('setTo', array('ww@ww.com', 'zz@zz.com')),
            array('setBody', 'helloWorld'),
            array('setSubject', 'helloEarth'),
        );
    }

    public function testSend()
    {
        $a = array(
            'smtp' => array(
                'tls' => true,
                'host' => 'smtp.gmail.com',
                'port' => 465,
                'username' => 'yourlogin',
                'password' => 'yourpwd',
             )
        );
        $adapter = new phpRack_Adapters_Mail_Transport_Smtp($a);
        $adapter->setTo('yourlogin@gmail.com');
        $adapter->setBody('Passed');
        $adapter->setSubject('Unit Test');

        try {
            $this->assertTrue($adapter->send());
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (strpos($msg, 'Wrong answer') === 0) {
                $this->markTestSkipped($msg);
            }
        }
    }

    /**
     * @expectedException Exception
     */
    public function testSendWithoutToException()
    {
        $arr = array('smtp');
        $adapter = new phpRack_Adapters_Mail_Transport_Smtp($arr);
        $adapter->setBody('Test');
        $adapter->send();
    }

    /**
     * @expectedException Exception
     */
    public function testSendWithoutBodyException()
    {
        $arr = array('smtp');
        $adapter = new phpRack_Adapters_Mail_Transport_Smtp($arr);
        $adapter->setTo('sldksfI483dsr@mailinator2.com');
        $adapter->send();
    }
}
