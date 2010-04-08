<?php
/**
 * @version $Id$
 */

define('PHPRACK_PATH', realpath(dirname(__FILE__) . '/../phpRack'));

/**
 * This variable is used below, in setUp()
 * @see AbstractTest::setUp()
 * @var string[]
 */
global $phpRackConfig;
$phpRackConfig = array(
    'dir' => dirname(__FILE__) . '/integration-tests',
);

/**
 * These variables are normally set in bootstrap.php
 * but here we should set them explicitly, for tests only
 */
$_SERVER['REQUEST_URI'] = 'no-URL-it-is-testing.com';
define('PHPRACK_AJAX_TAG', 'testing-tag');
define('PHPRACK_AJAX_TOKEN', 'testing-token');

/**
 * Added to avoid an error if tests are executed in different order
 * and PHPRACK_VERSION is required but doesn't defined 
 * @see layout.phtml
 */
define('PHPRACK_VERSION', '0.1test');

/**
 * @see PHPUnit_Framework_TestCase
 */
require_once 'PHPUnit/Framework/TestCase.php';

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var phpRack_Runner
     * @see setUp()
     * @see tearDown()
     */
    protected $_runner;

    /**
     * @var phpRack_Test
     * @see setUp()
     * @see tearDown()
     */
    protected $_test;

    /**
     * Unit test bootstrapper, called by PHPUnit_Framework_TestCase
     *
     * Your custom unit test inside the framework should look like
     * the following (pay attention how and were we get {@link phpRack_Assertion} object,
     * and {@link phpRack_Result} object):
     *
     * <code>
     * class MyTest extends AbstractTest {
     *   public function testMySpecificPackageWorksFine() {
     *     $isValid = $this->_test->assert->php->atLeast('5.2');
     *     $this->assertTrue(is_bool($isValid));
     *     $this->assertTrue($this->_test->assert->getResult());
     *   }
     * }
     * </code>
     *
     * You should NOT instantiate tests, assertions or test results explicitly
     * in your unit tests. Only if you're testing the mechanism of their
     * instantiation. If you're testing packages, use the approach explained
     * above in PHP snippet.
     * 
     * @see PHPUnit_Framework_TestCase::run()
     * @see tearDown()
     */
    protected function setUp()
    {
        global $phpRackConfig;
        /**
         * @see phpRack_Runner
         */
        require_once PHPRACK_PATH . '/Runner.php';
        $this->_runner = new phpRack_Runner($phpRackConfig);

        /**
         * This test is used as a template for all other packages/assertions
         * testing. No matter what particular test we use here.
         * @see phpRack_Test
         */
        require_once PHPRACK_PATH . '/Test.php';
        $this->_test = phpRack_Test::factory(
            PHPRACK_PATH . '/../test/integration-tests/CustomTest.php',
            $this->_runner
        );
    }

    /**
     * Unit test finalizer, called by PHPUnit_Framework_TestCase
     *
     * @see PHPUnit_Framework_TestCase::run()
     * @see setUp()
     */
    protected function tearDown()
    {
        unset($this->_test);
        unset($this->_runner);
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
