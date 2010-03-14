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
 * @see phpRack_Result
 */
require_once PHPRACK_PATH . '/Result.php';

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * One test assertion package
 *
 * @package Tests
 */
class phpRack_Package
{
    
    /**
     * Static instances of packages
     *
     * @var phpRack_Package
     */
    protected static $_packages = array();
    
    /**
     * Result collector
     *
     * @var phpRack_Result
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
     * @param phpRack_Result Result to use
     * @return void
     */
    public function __construct(phpRack_Result $result)
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
        throw new Exception(
            sprintf(
                "Method '%s' is absent in '%s' package, %d args passed",
                $name,
                $this->getName(),
                count($args)
            )
        );
    }

    /**
     * Create new assertion
     *
     * @param string Name of the package, like "php/version"
     * @param phpRack_Result Collector of log lines
     * @return phpRack_Package
     * @throws Exception
     */
    public static function factory($name, phpRack_Result $result) 
    {
        $sectors = array_map(
            create_function('$a', 'return ucfirst($a);'),
            explode('/', $name)
        );
        $className = 'phpRack_Package_' . implode('_', $sectors);
        
        $packageFile = PHPRACK_PATH . '/Package/' . implode('/', $sectors) . '.php';
        if (!file_exists($packageFile)) {
            throw new Exception("Package '$name' is absent in phpRack: '{$packageFile}'");
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
     * @return phpRack_Package
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
        $sectors = explode('_', get_class($this)); // e.g. "phpRack_Package_Php_Version"
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
        if ($this->_latestCallSuccess) {
            $this->_log($action);
        }
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
        if (!$this->_latestCallSuccess) {
            $this->_log($action);
        }
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
        $this->_log('[' . phpRack_Test::FAILURE . '] ' . $log);
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
        $this->_log('[' . phpRack_Test::OK . '] ' . $log);
    }
        
    /**
     * Just log a line
     *
     * @param string String to log
     * @return void
     */
    protected function _log($log) 
    {
        $this->_result->addLog($log);
    }
    
    /**
     * Converts file name from any form possible to an absolute path
     *
     * For example, you can use it like this, inside any package:
     *
     * <code>
     * // convert it to PHPRACK_PATH . '/../test.php'
     * $file = $this->_convertFileName('/../test.php');
     * // returns '/home/my/test.php'
     * $file = $this->_convertFileName('/home/my/test.php');
     * // returns 'c:/Windows/System32/my.dll'
     * $file = $this->_convertFileName('c:/Windows/System32/my.dll');
     * </code>
     * 
     * If the file not found, it doesn't affect the result of this method. The
     * result always contain an absolute path of the file. This method doesn't
     * do any operations with the file, just re-constructs its name.
     *
     * @param string File name, as it is provided (raw form)
     * @return string
     * @todo #5 we should extensively unit-test this method
     */
    protected function _convertFileName($fileName) 
    {
        switch (true) {
            // relative name started with '/..', or '../', or './'
            case preg_match('/^\/?\.\.?\//', $fileName):
                return PHPRACK_PATH . '/' . $fileName;

            default:
                return $fileName;
        }
    }
        
}
