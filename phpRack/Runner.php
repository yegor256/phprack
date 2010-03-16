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
 * @see phpRack_Runner_AuthResult
 */
require_once PHPRACK_PATH . '/Runner/AuthResult.php';

/**
 * Run all tests together, or one by one
 *
 * First you should create an instance of this class, providing it an array
 * of options. Then you can either run individual test or all tests in a 
 * test suite:
 *
 * <code>
 * $runner = new phpRack_Runner(array('dir'=>'/path/to/my-tests'));
 * echo $runner->runSuite();
 * </code>
 *
 * This code will give you a plain-text report of all tests in your collection,
 * executed and logged.
 *
 * @package Tests
 */
class phpRack_Runner
{
    
    /**
     * This is how you should name your test files, if you want
     * them to be found by the Runner
     */
    const TEST_PATTERN = '/^(\w+Test)\.php$/i';
    
    /**
     * List of options, which are changeable
     *
     * @var array
     * @see __construct()
     */
    protected $_options = array(
        'dir' => null,
        'auth' => array(),
    );
    
    /**
     * Auth result, if authentication was already performed
     *
     * @var phpRack_Runner_AuthResult
     * @see authenticate()
     */
    protected $_authResult = null;
    
    /**
     * Construct the class
     *
     * @param array Options to set to the class
     * @return void
     * @throws Exception If an option is invalid
     * @see $this->_options
     */
    public function __construct(array $options) 
    {
        foreach ($options as $option=>$value) {
            if (!array_key_exists($option, $this->_options)) {
                throw new Exception("Option '{$option}' is not recognized");
            }
            $this->_options[$option] = $value;
        }
    }
    
    /**
     * Authenticate the user before running any tests
     *
     * @param string Login of the user
     * @param string Secret password of the user
     * @return phpRack_Runner_AuthResult
     * @see $this->_authResult
     * @todo #8 implement it properly
     */
    public function authenticate($login, $password) 
    {
        return new phpRack_Runner_AuthResult();
    }
    
    /**
     * Get tests location directory
     *
     * @return string
     * @throws Exception If directory is absent
     * @see $this->_options
     */
    public function getDir() 
    {
        $dir = $this->_options['dir'];
        if (!file_exists($dir)) {
            throw new Exception("Test directory '{$dir}' is not found");
        }
        return realpath($dir);
    }
    
    /**
     * Get full list of tests, in array
     *
     * @return phpRack_Test[]
     */
    public function getTests() 
    {
        $tests = array();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->getDir())) as $file) {
            if (!preg_match(self::TEST_PATTERN, $file->getFilename())) {
                continue;
            }
                
            $tests[] = phpRack_Test::factory(strval($file), $this);
        }
        return $tests;
    }
    
    /**
     * Run all tests and return a text report about their execution
     *
     * @return string
     * @see $this->getTests()
     * @see $this->run()
     */
    public function runSuite() 
    {
        $tests = $this->getTests();
        $report = '';
        foreach ($tests as $test) {
            $result = $test->run();
            $report .= sprintf(
                "%s: %s\n%s\n",
                $test->getLabel(),
                $result->wasSuccessful() ? phpRack_Test::OK : phpRack_Test::FAILURE,
                $result->getLog()
            );
        }
        return $report;
    }
    
    /**
     * Run one test and return JSON result
     *
     * @param string Test file name (absolute name of PHP file)
     * @param string Unique token to return back, if required
     * @return string JSON
     * @throws Exception
     */
    public function run($fileName, $token = 'token') 
    {
        $test = phpRack_Test::factory($fileName, $this);
        
        $result = $test->run();
        return json_encode(
            array(
                'success' => $result->wasSuccessful(),
                'log' => $result->getLog(),
                PHPRACK_AJAX_TOKEN => $token,
            )
        );
    }
    
}
