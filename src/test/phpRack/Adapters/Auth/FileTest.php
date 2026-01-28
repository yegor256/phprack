<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

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

    protected function setUp(): void
    {
        $this->_config['htpasswd'] = @tempnam(sys_get_temp_dir(), 'prk');
        if ($this->_config['htpasswd'] === false) {
            $this->markTestSkipped("System can't create temp file");
        }

        // making temp file and writing data into it
        $handle = @fopen($this->_config['htpasswd'], "w+");
        if ($handle === false) {
            $this->markTestSkipped("System can't open temp file");
        }
        fwrite($handle, "test:phprack");
        fclose($handle);

        $this->_auth = new phpRack_Adapters_Auth_File();
        $this->_auth->setOptions($this->_config);
    }

    protected function tearDown(): void
    {
        @unlink($this->_config['htpasswd']);
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
