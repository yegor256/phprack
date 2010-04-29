<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

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
     * @dataProvider testPublicFuncProvider
     */
    public function testPublicFunc($a, $b)
    {
        $result = $this->_adapter->{$a}($b);
        $this->assertTrue(
            $result instanceof phpRack_Adapters_Notifier_Mail_Sendmail
        );
    }

    public function testPublicFuncProvider()
    {
        return array(
            array('setTo', 'test1@example.com'),
            array('setTo', 'test2@example.com'),
            array('setBody', 'helloWorld'),
            array('setSubject', 'helloEarth'),
        );
    }

    public function testSend()
    {
        $this->_adapter->setTo('test3@example.com');
        $this->_adapter->setBody('Test');
        $this->assertTrue($this->_adapter->send());
    }

    /**
     * @expectedException Exception
     */
    public function testSendWithoutToException()
    {
        $this->_adapter->setBody('Test');
        $this->_adapter->send();
    }

    /**
     * @expectedException Exception
     */
    public function testSendWithoutBodyException()
    {
        $this->_adapter->setTo('test5@example.com');
        $this->_adapter->send();
    }
}
