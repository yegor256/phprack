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
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * Result of a test execution
 *
 * @package Tests
 * @see phpRack_Assertion::__construct()
 */
class phpRack_Result
{
    
    /**
     * Log lines
     *
     * @var string
     * @see getLog()
     */
    protected $_lines;
    
    /**
     * Total result is SUCCESS?
     *
     * @var boolean
     * @see wasSuccessful()
     */
    protected $_success;
    
    /**
     * When this result was created by test
     *
     * @var float
     * @see clean()
     * @see getDuration()
     */
    protected $_started;

    /**
     * Test object which is owner of this result object,
     * used for give ability to set ajax options from phpRack_Package
     *
     * @var phpRack_Test
     * @see setTest()
     * @see getTest()
     */
    protected $_test;
    
    /**
     * Construct the class
     *
     * @param phpRack_Test Test, which pushes results here
     * @return void
     * @see phpRack_Assertion::__construct()
     * @todo #28 we should disable this NULL option for the parameter and 
     *      make sure that this constructor is NOT called from anywhere
     *      except phpRack_Assertion::__construct(). Now this constructor
     *      is extensively used in almost every unit test, which is an invalid
     *      approach. We should create packages only in unit tests, and the
     *      package created should take care about RESULT object inside it.
     *      Thus, in AbstractTest::setUp() we should create an instance of
     *      phpRack_Test and all unit tests should be able to use this instance.
     */
    public function __construct(phpRack_Test $test = null)
    {
        $this->_test = $test;
        $this->clean();
    }
    
    /**
     * Set total result to FAILURE
     *
     * @return void
     * @see phpRack_Package::_failure()
     */
    public function fail() 
    {
        $this->_success = false;
    }
    
    /**
     * Was the test successful?
     *
     * @return boolean
     * @see phpRack_Runner::runSuite()
     */
    public function wasSuccessful() 
    {
        return $this->_success;
    }
    
    /**
     * Get full log of the result
     *
     * @return string
     * @see phpRack_Runner::run()
     */
    public function getLog() 
    {
        return implode("\n", $this->_lines);
    }
    
    /**
     * Get log of assertions only, without any other messages
     *
     * @return string
     * @see phpRack_Runner::runSuite()
     */
    public function getPureLog() 
    {
        return implode("\n", preg_grep('/^\[[A-Z]+\]\s/', $this->_lines));
    }
    
    /**
     * Get result lifetime, duration in seconds
     *
     * @return void
     * @see phpRack_Runner::runSuite()
     */
    public function getDuration() 
    {
        return microtime(true) - $this->_started;
    }
    
    /**
     * Add new log line
     *
     * @param string Log line to add
     * @return $this
     * @see phpRack_Package::_log()
     */
    public function addLog($line) 
    {
        $this->_lines[] = $line;
        return $this;
    }
    
    /**
     * Clean log
     *
     * @return void
     * @see phpRack_Test::run()
     */
    public function clean() 
    {
        $this->_success = true;
        $this->_lines = array();
        $this->_started = microtime(true);
    }

    /**
     * Get test which is owner of this result object
     *
     * @return phpRack_Test
     * @see phpRack_Package_Disc_File::tail()
     */
    public function getTest()
    {
        return $this->_test;
    }
}
