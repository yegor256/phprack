<?php

require_once 'AbstractTest.php';

class RunnerTest extends AbstractTest
{

    public function setUp()
    {
        parent::setUp();
        require_once PHPRACK_PATH . '/Runner.php';
        global $phpRackConfig;
        $this->_runner = new PhpRack_Runner($phpRackConfig);
    }
    
    public function testTestFilesAreCollectedCorrectly()
    {
        $tests = $this->_runner->getTests();
        $this->assertFalse(empty($tests), "List of tests is empty, why?");
    }
    
    public function testIndividualTestCanBeExecuted()
    {
        $tests = $this->_runner->getTests();
        $test = array_shift($tests);
        $result = $test->run();
        $this->assertTrue($result instanceof PhpRack_Result);
        $this->assertTrue(is_bool($result->wasSuccessful()));
        $this->assertTrue(is_string($result->getLog()));
    }
        
}
