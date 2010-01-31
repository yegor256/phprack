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

require_once PHPRACK_PATH . '/Test.php';

/**
 *
 *
 * @package Tests
 */
class PhpRack_Runner
{
    
    const TEST_PATTERN = '/^(.*Test)\.php$/i';
    
    /**
     * Directory with tests
     *
     * @var string
     */
    protected $_dir;
    
    /**
     * Construct the class
     *
     * @return void
     */
    public function __construct(array $config) 
    {
        $this->_dir = $config['dir'];
    }
    
    /**
     * Get full list of tests, in array
     *
     * @return PhpRack_Test[]
     */
    public function getTests() 
    {
        $tests = array();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_dir)) as $file) {
            if (!preg_match(self::TEST_PATTERN, $file->getFilename(), $matches)) {
                continue;
            }
                
            $className = $matches[1];    
            require_once $file;
            $tests[] = new $className(strval($file));
        }
        return $tests;
    }
    
    /**
     * Run one test and return JSON result
     *
     * @param string Test file name (absolute name of PHP file)
     * @return JSON
     * @throws Exception
     */
    public function run($fileName) 
    {
        if (!file_exists($fileName)) {
            throw new Exception("File '{$fileName}' is not found");
        }
        
        if (!preg_match(self::TEST_PATTERN, $fileName, $matches)) {
            throw new Exception("File '{$fileName}' is not named properly, can't run it");
        }
        
        $className = pathinfo($fileName, PATHINFO_FILENAME);
        require_once $fileName;
        $test = new $className($fileName);
        
        $result = $test->run();
        return json_encode(
            array(
                'success' => $result->wasSuccessful(),
                'log' => $result->getLog()
            )
        );
    }
    
}
