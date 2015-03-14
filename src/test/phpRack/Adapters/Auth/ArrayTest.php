<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Auth_Array
 */
require_once PHPRACK_PATH . '/Adapters/Auth/Array.php';

class Adapters_Auth_ArrayTest extends AbstractTest
{
    /**
     * @var array
     */
    private $_config = array(
        'htpasswd' => array('test' => 'phprack')
    );

    /**
     * @var phpRack_Adapters_Auth_Array
     */
    private $_auth;

    protected function setUp()
    {
        $this->_auth = new phpRack_Adapters_Auth_Array();
        $this->_auth->setOptions($this->_config);
    }

    public function testValidAuthArray()
    {
        $request = array();
        $request['login'] = current(array_keys($this->_config['htpasswd']));
        $request['hash']  = md5($this->_config['htpasswd'][$request['login']]);

        $this->assertTrue(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    public function testNotValidAuthArray()
    {
        $request = array();
        $request['login'] = current(array_keys($this->_config['htpasswd']));
        $request['hash']  = md5($this->_config['htpasswd'][$request['login']] . '_');

        $this->assertFalse(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    public function testNotValidOnlyLoginAuthArray()
    {
        $request = array(
            'login' => current(array_keys($this->_config['htpasswd']))
        );
        $this->assertFalse(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    public function testNotValidOnlyPwdAuthArray()
    {
        $request = array(
            'hash' => md5(current(array_values($this->_config['htpasswd'])))
        );
        $this->assertFalse(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }
}
