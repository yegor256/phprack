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
 * One single test assertion
 *
 * @package Tests
 */
class phpRack_Assertion
{
    
    /**
     * Static instances of assertions
     *
     * @var string
     */
    protected static $_assertions = array();
    
    /**
     * Result collector
     *
     * @var phpRack_Result
     */
    protected $_result;
    
    /**
     * Construct the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->_result = new phpRack_Result();
    }

    /**
     * Create new assertion
     *
     * @param string Absolute name of PHP file with test
     * @return phpRack_Assertion
     */
    public static function factory($test) 
    {
        if (!isset(self::$_assertions[$test])) {
            self::$_assertions[$test] = new self();
        }
        return self::$_assertions[$test];
    }
    
    /**
     * Dispatcher of calls to packages
     *
     * @param string Name of the package to get
     * @return phpRack_Package
     */
    public function __get($name) 
    {
        return phpRack_Package::factory($name, $this->_result);
    }
        
    /**
     * Call method, any one
     *
     * @param string Name of the method to call
     * @param array Arguments to pass
     * @return mixed
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
     */
    public function getResult() 
    {
        return $this->_result;
    }
    
}
