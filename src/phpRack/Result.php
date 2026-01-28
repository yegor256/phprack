<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage core
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * Result of a test execution
 *
 * @see phpRack_Assertion#__construct()
 * @package Tests
 * @subpackage core
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
     */
    public function __construct(phpRack_Test $test)
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
