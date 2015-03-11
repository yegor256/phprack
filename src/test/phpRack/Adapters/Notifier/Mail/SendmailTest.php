<?php
/**
 * @version $Id$
 */

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

    protected function setUp()
    {
        parent::setUp();
        $this->_adapter = new phpRack_Adapters_Notifier_Mail_Sendmail();
    }

    protected function tearDown()
    {
        unset($this->_adapter);
        parent::tearDown();
    }

    /**
     * @dataProvider testPublicFunctionsProvider
     */
    public function testPublicFunctionsWork($a, $b)
    {
        $result = $this->_adapter->{$a}($b);
        $this->assertTrue(
            $result instanceof phpRack_Adapters_Notifier_Mail_Sendmail
        );
    }

    public function testPublicFunctionsProvider()
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
        $this->_adapter->setTo('test@phprack.com');
        $this->_adapter->setBody('This is test');
        $this->assertTrue($this->_adapter->send());
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testSendWithoutToException()
    {
        $this->_adapter->setBody('Test');
        $this->_adapter->send();
    }

    /**
     * @expectedException phpRack_Exception
     */
    public function testSendWithoutBodyException()
    {
        $this->_adapter->setTo('test@phprack.com');
        $this->_adapter->send();
    }
}
