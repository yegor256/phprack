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
 * @see phpRack_Runner
 */
require_once PHPRACK_PATH . '/Runner.php';

/**
 * @see phpRack_Assertion
 */
require_once PHPRACK_PATH . '/Assertion.php';

/**
 * Parent class of all integration tests
 *
 * @package Tests
 */
abstract class phpRack_Test
{

    const OK = 'OK';
    const FAILURE = 'FAILURE';

    /**
     * ID of the test (unique in the system)
     *
     * @var string
     */
    protected $_fileName;
    
    /**
     * Runner of tests
     *
     * @var phpRack_Runner
     */
    protected $_runner;
    
    /**
     * Construct the class
     *
     * @param string ID of the test, absolute (!) file name
     * @param phpRack_Runner Instance of test runner
     * @return void
     */
    private final function __construct($fileName, phpRack_Runner $runner)
    {
        $this->_fileName = realpath($fileName);
        $this->_runner = $runner;
    }
    
    /**
     * Create new instance of the class, using PHP absolute file name
     *
     * @param string ID of the test, absolute (!) file name
     * @param phpRack_Runner Instance of test runner
     * @return phpRack_Test
     * @throws Exception
     */
    public static function factory($fileName, phpRack_Runner $runner) 
    {
        if (!file_exists($fileName)) {
            throw new Exception("File '{$fileName}' is not found");
        }
        
        if (!preg_match(phpRack_Runner::TEST_PATTERN, $fileName)) {
            throw new Exception("File '{$fileName}' is not named properly, can't run it");
        }
        
        $className = pathinfo($fileName, PATHINFO_FILENAME);
        require_once $fileName;
        return new $className($fileName, $runner);
    }
    
    /**
     * Dispatches property-like calls to the class
     *
     * @param string Name of the property to get
     * @return mixed
     * @throws Exception If nothing found
     */
    public final function __get($name) 
    {
        if ($name == 'assert') {
            return phpRack_Assertion::factory(__FILE__);
        }
        throw new Exception("Property '{$name}' not found in " . get_class($this));
    }
    
    /**
     * Get unique test ID (file name of the test)
     *
     * @return string
     * @see $this->_fileName
     */
    public function getFileName() 
    {
        return $this->_fileName;
    }
    
    /**
     * Get label of the test
     *
     * @return string
     */
    public function getLabel() 
    {
        return ltrim(substr($this->_fileName, strlen($this->_runner->getDir())), '/');
    }
    
    /**
     * Run the test and return result
     *
     * @return phpRack_Result
     */
    public final function run() 
    {
        // clean all previous results, if any
        $this->assert->getResult()->clean();
        
        // start execution of the test with time calculation
        $start = microtime(true);
        
        // find all methods that start with "test" and call them
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
        
        // add final log line, summarizing the test execution
        $this->assert->getResult()->addLog(
            sprintf(
                'Finished %s, %0.3fsec',
                get_class($this),
                microtime(true) - $start
            )
        );
        
        // return instance of phpRack_Result class
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