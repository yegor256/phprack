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
 * Result of a test execution
 *
 * @package Tests
 */
class phpRack_Result
{
    
    /**
     * Log lines
     *
     * @var string
     */
    protected $_lines;
    
    /**
     * Total result is SUCCESS?
     *
     * @var boolean
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
     * Construct the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->clean();
    }
    
    /**
     * Set total result to FAILURE
     *
     * @return void
     */
    public function fail() 
    {
        $this->_success = false;
    }
    
    /**
     * Was the test successful?
     *
     * @return boolean
     */
    public function wasSuccessful() 
    {
        return $this->_success;
    }
    
    /**
     * Get full log of the result
     *
     * @return string
     */
    public function getLog() 
    {
        return implode("\n", $this->_lines);
    }
    
    /**
     * Get log of assertions only, without any other messages
     *
     * @return string
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
     * @see clean()
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
     */
    public function clean() 
    {
        $this->_success = true;
        $this->_lines = array();
        $this->_started = microtime(true);
    }
        
}
