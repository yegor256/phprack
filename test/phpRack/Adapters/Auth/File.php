<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Auth_File
 */
require_once PHPRACK_PATH . '/Adapters/Auth/File.php';

class Adapters_Auth_FileTest extends AbstractTest
{
    /**
     * @var array
     */
    private $_config = array();

    /**
     * @var phpRack_Adapters_Auth_File
     */
    private $_auth;

    protected function setUp()
    {
        $this->_config['htpasswd'] = dirname(__FILE__) . '/_files/_htpasswd';
        
        $this->_auth = new phpRack_Adapters_Auth_File();
        $this->_auth->setOptions($this->_config);
    }

    public function testValidAuthFile()
    {
        $info = $this->_getLoginInfo();
        $request = array(
            'login' => $info['login'], 'hash'  => $info['hash']
        );

        $this->assertTrue(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    public function testNotValidAuthFile()
    {
        $info = $this->_getLoginInfo();
        $request = array(
            'login' => $info['login'], 'hash'  => $info['hash'] . '_'
        );

        $this->assertFalse(
            $this->_auth->setRequest($request)->authenticate()->isValid()
        );
    }

    private function _getLoginInfo()
    {
        $data = file($this->_config['htpasswd']);
        $info = array();
        list($info['login'], $info['hash']) = explode(':', $data[0]);
        return $info;
    }
}
