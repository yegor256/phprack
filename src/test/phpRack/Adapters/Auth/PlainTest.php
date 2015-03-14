<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Auth_Plain
 */
require_once PHPRACK_PATH . '/Adapters/Auth/Plain.php';

class Adapters_Auth_PlainTest extends AbstractTest
{
    /**
     * @var array
     */
    private $_config = array(
        'auth' => array('username' => 'test', 'password' => 'phprack')
    );

    /**
     * @var phpRack_Adapters_Auth_Plain
     */
    private $_auth;

    protected function setUp()
    {
        $this->_auth = new phpRack_Adapters_Auth_Plain();
        $this->_auth->setOptions($this->_config);
    }

    public function testValidAuthPlain()
    {
        $request = array(
            'login' => $this->_config['auth']['username'],
            'hash'  => md5($this->_config['auth']['password'])
        );

        $this->assertTrue(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    public function testNotValidAuthPlain()
    {
        $request = array(
            'login' => $this->_config['auth']['username'],
            'hash'  => md5("{$this->_config['auth']['password']}_")
        );

        $this->assertFalse(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    public function testNotValidOnlyLoginAuthPlain()
    {
        $request = array(
            'login' => $this->_config['auth']['username']
        );
        $this->assertFalse(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    public function testNotValidOnlyPwdAuthPlain()
    {
        $request = array(
            'hash' => md5($this->_config['auth']['password'])
        );
        $this->assertFalse(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }
}
