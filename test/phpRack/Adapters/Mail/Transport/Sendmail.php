<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Mail_Transport_Sendmail
 */
require_once PHPRACK_PATH . '/Adapters/Mail/Transport/Sendmail.php';

class Adapters_Mail_Abstract_SendmailTest extends AbstractTest
{
    /**
     * Sendmail adapter
     *
     * @var Adapters_Mail_Abstract_Sendmail
     */
    protected $_adapter;
    
    protected function setUp()
    {
        parent::setUp();
        $a = array();
        $this->_adapter = new phpRack_Adapters_Mail_Transport_Sendmail($a);
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
            $result instanceof phpRack_Adapters_Mail_Transport_Sendmail
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
        $this->_adapter->setTo('sldksfI483dsr@mailinator2.com');
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
        $this->_adapter->setTo('sldksfI483dsr@mailinator2.com');
        $this->_adapter->send();
    }
}
