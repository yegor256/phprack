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
 *
 *
 * @package Tests
 */
class PhpRack_Assertion
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
     * @var PhpRack_Result
     */
    protected $_result;
    
    /**
     * Construct the class
     *
     * @return void
     */
    public function __construct()
    {
        require_once PHPRACK_PATH . '/Result.php';
        $this->_result = new PhpRack_Result();
    }

    /**
     * Create new assertion
     *
     * @param string Absolute name of PHP file with test
     * @return PhpRack_Assertion
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
     * @return PhpRack_Package
     */
    public function __get($name) 
    {
        require_once PHPRACK_PATH . '/Package.php';
        return PhpRack_Package::factory($name, $this->_result);
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
        require_once PHPRACK_PATH . '/Package.php';
        return call_user_func_array(
            array(
                PhpRack_Package::factory('simple', $this->_result),
                $name
            ),
            $args
        );
    }    
    
    /**
     * Get instance of result collector
     *
     * @return PhpRack_Result
     */
    public function getResult() 
    {
        return $this->_result;
    }
    
}
