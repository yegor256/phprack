<?php
/**
 * @version $Id$
 */

define('PHPRACK_PATH', realpath(dirname(__FILE__) . '/../phpRack'));

global $phpRackConfig;
$phpRackConfig = array(
    'dir' => dirname(__FILE__) . '/integration-tests',
);

// These variables are normally set in bootstrap.php
// but here we should set them explicitly, for tests only
$_SERVER['REQUEST_URI'] = 'no-URL-it-is-testing.com';
define('PHPRACK_AJAX_TAG', 'testing-tag');
define('PHPRACK_AJAX_TOKEN', 'testing-token');

/**
 * @see PHPUnit_Framework_TestCase
 */
require_once 'PHPUnit/Framework/TestCase.php';

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    protected $_runner;

    /**
     * @todo #28 Please write how should look correct method of object creation here
     *           and example for child unit test classes.
     *
     *           We must specify some integration test path if we want create test object.
     *           What path should be here, because we just need phpRack_Test object,
     *           but we don't use methods which are defined in it like(testFileContent(), testFile())?
     */
    protected function setUp()
    {
        global $phpRackConfig;
        /**
         * @see phpRack_Runner
         */
        require_once PHPRACK_PATH . '/Runner.php';
        $this->_runner = new phpRack_Runner($phpRackConfig);

        /*
        $testPath = PHPRACK_PATH . '/../test/integration-tests/Disc/FileTest.php';
        $this->_test = phpRack_Test::factory($testPath, $this->_runner);
        $this->_assert = $this->_test->assert;
        $this->_result = $this->_assert->getResult();
        */
    }
    
    /**
     * Log one message in testing
     *
     * @param string Message to log
     * @return void
     */
    protected function _log($message) 
    {
        echo $message;
    }
    
}
