<?php
/**
 * phpRack: Integration Testing Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available 
 * through the world-wide-web at this URL: http://www.phprack.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phprack.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) phpRack.com
 * @version $Id$
 * @category phpRack
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * @see phpRack_Result
 */
require_once PHPRACK_PATH . '/Result.php';

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * One single test assertion
 *
 * @package Tests
 * @see phpRack_Test::__get()
 */
class phpRack_Assertion
{
    
    /**
     * Static instances of assertions
     *
     * @var string
     * @see factory()
     */
    protected static $_assertions = array();
    
    /**
     * Result collector
     *
     * @var phpRack_Result
     * @see __construct()
     */
    protected $_result;
    
    /**
     * Construct the class
     *
     * @param phpRack_Test Test, which pushes results here
     * @return void
     * @see phpRack_Test::__get()
     */
    private function __construct(phpRack_Test $test)
    {
        $this->_result = new phpRack_Result($test);
    }

    /**
     * Create new assertion
     *
     * @param string Absolute name of PHP file with test
     * @param phpRack_Test Test that is using this assertion
     * @return phpRack_Assertion
     * @see phpRack_Test::__get()
     */
    public static function factory($name, phpRack_Test $test) 
    {
        if (!isset(self::$_assertions[$name])) {
            self::$_assertions[$name] = new self($test);
        }
        return self::$_assertions[$name];
    }
    
    /**
     * Dispatcher of calls to packages
     *
     * @param string Name of the package to get
     * @return phpRack_Package
     * @see phpRack_Test::_log() and many other methods inside Integration Tests
     */
    public function __get($name) 
    {
        return phpRack_Package::factory($name, $this->_result);
    }
        
    /**
     * Call method, any one
     *
     * This magic method will be called when you're using any assertion and 
     * some method inside it, for example:
     * 
     * <code>
     * // inside your instance of phpRack_Test:
     * $this->assert->php->extensions->isLoaded('simplexml');
     * </code>
     *
     * The call in the example will lead you to this method, and will call
     * __call('simplexml', array()).
     *
     * @param string Name of the method to call
     * @param array Arguments to pass
     * @return mixed
     * @see PhpConfigurationTest::testPhpExtensionsExist isLoaded() reaches this point
     */
    public function __call($name, array $args) 
    {
        return call_user_func_array(
            array(
                phpRack_Package::factory('simple', $this->_result),
                $name
            ),
            $args
        );
    }    
    
    /**
     * Get instance of result collector
     *
     * @return phpRack_Result
     * @see phpRack_Test::_log() and many other methods inside Integration Tests
     */
    public function getResult() 
    {
        return $this->_result;
    }
    
}
