<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage core
 */

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * Parent class of all test suites
 *
 * Suites are maintained as directories full of tests, inside "phpRack/Suite/library"
 * holder. When we {@link _addSuite()}, this class files all tests in this suite
 * directory and add them all to itself. Also, library contains individual
 * tests, which can be added to the suite by means of {@link _addTest()}.
 *
 * @package Tests
 * @subpackage core
 */
abstract class phpRack_Suite
{
    /**
     * Runner of tests
     * @todo #48 Will be used when #48 will be merged into trunk
     *
     * @var phpRack_Runner
     * @see __construct()
     * @see _addTest()
     * @see _addSuite()
     */
    //private $_runner;

    /**
     * Suite tests
     *
     * @var array of phpRack_Test
     * @see getTests()
     * @see _addTest()
     */
    private $_tests = array();

    /**
     * Create new instance of the class, using PHP absolute file name
     *
     * @param $label string ID of the suite, its label
     * @param $runner phpRack_Runner Instance of test runner
     * @return phpRack_Suite
     * @throws phpRack_Exception
     * @see _addSuite()
     * @see phpRack_Runner::getTests()
     */
    public static function factory($label, phpRack_Runner $runner)
    {
        $fileName = $runner->getDir() . '/' . $label;
        if (!file_exists($fileName)) {
            throw new phpRack_Exception("File '{$fileName}' is not found");
        }
        if (!preg_match(phpRack_Runner::SUITE_PATTERN, $fileName)) {
            throw new phpRack_Exception("File '{$fileName}' is not named properly, can't run it");
        }
        $className = pathinfo($fileName, PATHINFO_FILENAME);
        // workaround against ZCA static code analysis
        eval('require_once $fileName;');
        return new $className($runner);
    }

    /**
     * Get tests defined in this suite
     *
     * @return array of phpRack_Suite_Test
     * @see phpRack_Runner::getTests()
     */
    public function getTests()
    {
        return $this->_tests;
    }

    /**
     * Allow child class to add tests and sub suites by overwriting this
     * method
     *
     * @return void
     * @see __construct()
     */
    protected function __init()
    {

    }

    /**
     * Add suite
     *
     * Suite is a collection of tests. Name of the suite ($suiteName) is a name
     * of directory in "phpRack/Suite/library".
     *
     * @param $suiteName string Suite name
     * @param $config array config
     * @return $this
     * @throws phpRack_Exception if suite can't be found
     * @see MySuite::_init()
     * @see phpRack_Suite_Test
     */
    protected function _addSuite($suiteName, array $config = array())
    {
        $dir = PHPRACK_PATH . '/Suite/library/' . $suiteName;
        // create suite file iterator
        require_once PHPRACK_PATH . '/Adapters/Files/DirectoryFilterIterator.php';
        $iterator = phpRack_Adapters_Files_DirectoryFilterIterator::factory($dir)
            ->setExtensions('php');
        require_once PHPRACK_PATH . '/Suite/Test.php';
        foreach ($iterator as $file) {
            $label = substr($file->getRealPath(), strlen($dir));
            // phpRack_Exception is possible here
            $test = phpRack_Suite_Test::factory($suiteName . $label, $this->_runner);
            $test->setConfig($config);
            $this->_tests[] = $test;
        }
        return $this;
    }

    /**
     * Add test
     *
     * Test should be located in our test library, inside "phpRack/Suite/library"
     * directory, and should be inherited from {@link phpRack_Suite_Test} class.
     *
     * @param $testName string Suite name
     * @param $config array config
     * @return $this
     * @throws phpRack_Exception if test can't be found
     * @see MySuite::_init()
     * @see phpRack_Suite_Test
     */
    protected function _addTest($testName, array $config = array())
    {
        require_once PHPRACK_PATH . '/Suite/Test.php';
        // Exception is possible here
        $test = phpRack_Suite_Test::factory($testName . 'Test.php', $this->_runner);
        $test->setConfig($config);
        $this->_tests[] = $test;
        return $this;
    }

    /**
     * Construct the class
     *
     * @param $runner phpRack_Runner Instance of test runner
     * @return void
     * @see factory()
     */
    protected final function __construct(phpRack_Runner $runner)
    {
        $this->_runner = $runner;
        $this->_init();
    }
}
