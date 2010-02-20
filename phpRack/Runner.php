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
 * Run all tests together, or one by one
 *
 * First you should create an instance of this class, providing it an array
 * of options.
 *
 * @package Tests
 */
class PhpRack_Runner
{
    
    const TEST_PATTERN = '/^(.*Test)\.php$/i';
    
    /**
     * List of options
     *
     * @var array
     */
    protected $_options = array(
        'dir' => null,
        'auth' => array(),
    );
    
    /**
     * Construct the class
     *
     * @param array Options to set to the class
     * @return void
     * @throws Exception If an option is invalid
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
     * Get tests location directory
     *
     * @return string
     * @throws Exception If directory is absent
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
     * @return PhpRack_Test[]
     * @throws Exception
     */
    public function getTests() 
    {
        $tests = array();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->getDir())) as $file) {
            if (!preg_match(self::TEST_PATTERN, $file->getFilename(), $matches)) {
                continue;
            }
                
            $className = $matches[1];    
            require_once $file;
            $tests[] = new $className(strval($file), $this);
        }
        return $tests;
    }
    
    /**
     * Run all tests and return a text report about their execution
     *
     * @return string
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
     * @return JSON
     * @throws Exception
     */
    public function run($fileName, $token = 'token') 
    {
        if (!file_exists($fileName)) {
            throw new Exception("File '{$fileName}' is not found");
        }
        
        if (!preg_match(self::TEST_PATTERN, $fileName, $matches)) {
            throw new Exception("File '{$fileName}' is not named properly, can't run it");
        }
        
        $className = pathinfo($fileName, PATHINFO_FILENAME);
        require_once $fileName;
        $test = new $className($fileName, $this);
        
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
