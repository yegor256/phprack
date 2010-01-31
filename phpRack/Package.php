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
class PhpRack_Package
{
    
    /**
     * Static instances of packages
     *
     * @var PhpRack_Package
     */
    protected static $_packages = array();
    
    /**
     * Result collector
     *
     * @var PhpRack_Result
     */
    protected $_result;
    
    /**
     * Result of the latest call
     *
     * @var boolean TRUE means that the latest call was successful
     * @see _failure()
     * @see _success()
     */
    protected $_latestCallSuccess = false;
    
    /**
     * Construct the class
     *
     * @param PhpRack_Result Result to use
     * @return void
     */
    public function __construct(PhpRack_Result $result)
    {
        $this->_result = $result;
    }

    /**
     * Call to unknown function
     *
     * @param string Name of the method
     * @param array List of arguments
     * @return void
     * @throws Exception
     */
    public final function __call($name, array $args) 
    {
        throw new Exception("Method '{$name}' is absent in '{$this->getName()}' package");
    }

    /**
     * Create new assertion
     *
     * @param string Name of the package, like "php/version"
     * @param PhpRack_Result Collector of log lines
     * @return PhpRack_Package
     * @throws Exception
     */
    public static function factory($name, PhpRack_Result $result) 
    {
        $sectors = explode('/', $name);
        $className = 'PhpRack_Package_' . implode(
            '_',
            array_map(
                create_function('$a', 'return ucfirst($a);'), 
                $sectors
            )
        );
        
        $packageFile = PHPRACK_PATH . '/Package/' . implode('/', $sectors) . '.php';
        if (!file_exists($packageFile)) {
            throw new Exception("Package '$name' is absent in phpRack");
        }
        
        require_once $packageFile;
        
        if (!isset(self::$_packages[$className])) {
            self::$_packages[$className] = new $className($result);
        }
        return self::$_packages[$className];
    }
    
    /**
     * Dispatcher of calls to packages
     *
     * @param string Name of the property to get
     * @return PhpRack_Package
     */
    public function __get($name)
    {
        return self::factory($this->getName() . '/' . $name, $this->_result);
    }
    
    /**
     * Get my name, like: "php/version"
     *
     * @return string
     */
    public function getName() 
    {
        $sectors = explode('_', get_class($this)); // e.g. "PhpRack_Package_Php_Version"
        return implode(
            '/', 
            array_slice(
                array_map(
                    create_function('$a', 'return strtolower($a[0]) . substr($a, 1);'),
                    $sectors
                ), 
                2
            )
        );
    }
    
    /**
     * What to do on success?
     *
     * @param mixed What to do? STRING will log this string
     * @return $this
     */
    public final function onSuccess($action) 
    {
        if ($this->_latestCallSuccess)
            $this->_result->addLog($action);
        return $this;
    }
        
    /**
     * What to do on failure?
     *
     * @param mixed What to do? STRING will log this string
     * @return $this
     */
    public final function onFailure($action) 
    {
        if (!$this->_latestCallSuccess)
            $this->_result->addLog($action);
        return $this;
    }
    
    /**
     * Call failed
     *
     * @param string String to log
     * @return void
     */
    protected function _failure($log) 
    {
        $this->_latestCallSuccess = false;
        $this->_result->fail();
        $this->_result->addLog('[FAILURE] ' . $log);
    }
        
    /**
     * Call was successful
     *
     * @param string String to log
     * @return void
     */
    protected function _success($log) 
    {
        $this->_latestCallSuccess = true;
        $this->_result->addLog('[OK] ' . $log);
    }
        
}
