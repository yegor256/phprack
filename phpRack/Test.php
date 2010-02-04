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
 * Parent class of all integration tests
 *
 * @package Tests
 */
abstract class PhpRack_Test
{

    /**
     * ID of the test (unique in the system)
     *
     * @var string
     */
    protected $_fileName;
    
    /**
     * Construct the class
     *
     * @param string ID of the test, file name
     * @return void
     */
    public final function __construct($fileName)
    {
        $this->_fileName = realpath($fileName);
    }
    
    /**
     * Dispatches property-like calls to the class
     *
     * @param string Name of the property to get
     * @return mixed
     */
    public final function __get($name) 
    {
        if ($name == 'assert') {
            require_once PHPRACK_PATH . '/Assertion.php';
            return PhpRack_Assertion::factory(__FILE__);
        }
    }
    
    /**
     * Get unique test ID (file name of the test)
     *
     * @return string
     */
    public function getFileName() 
    {
        return $this->_fileName;
    }
    
    /**
     * Run the test and return result
     *
     * @return PhpRack_Result
     */
    public final function run() 
    {
        $start = microtime(true);
        
        $rc = new ReflectionClass($this);
        foreach ($rc->getMethods() as $method) {
            if (!preg_match('/^test/', $method->getName())) {
                continue;
            }
            try {
                $this->{$method->getName()}();
            } catch (Exception $e) {
                $this->assert->getResult()->addLog(
                    sprintf(
                        '%s: %s "%s"',
                        $method->getName(),
                        get_class($e),
                        $e->getMessage()
                    )
                )
                ->fail();
            }
        }
        
        $this->assert->getResult()->addLog(
            sprintf(
                'Finished %s, %0.3fsec',
                get_class($this),
                microtime(true) - $start
            )
        );
        
        return $this->assert->getResult();
    }
    
    /**
     * Log one message
     *
     * @param string The message
     * @return void
     */
    protected function _log($message) 
    {
        $this->assert->getResult()->addLog($message);
    }
    
}