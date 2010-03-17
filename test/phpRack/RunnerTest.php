<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Runner
 */
require_once PHPRACK_PATH . '/Runner.php';

/**
 * @see phpRack_Result
 */
require_once PHPRACK_PATH . '/Result.php';

class RunnerTest extends AbstractTest
{

    public function setUp()
    {
        parent::setUp();
        global $phpRackConfig;
        $this->_runner = new phpRack_Runner($phpRackConfig);
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
        $this->assertTrue($result instanceof phpRack_Result);
        $this->assertTrue(is_bool($result->wasSuccessful()));
        $this->assertTrue(is_string($result->getLog()));
    }

    public function testDefaultTimeZoneNotSet()
    {
        ini_set('date.timezone', null);
        $tests = $this->_runner->getTests();
        $result = $tests[0]->run();
        $defTimeZoneMessage  = 'INI setting date.timezone is not set. EST set as the time zone.';
        $defTimeZoneMessage .= 'Please set date.timezone to you current time zone'; 
        $this->assertTrue(FALSE !== strpos($result->getLog(), $defTimeZoneMessage), 'Default TZ warning missing');
    }

    public function testDefaultTimeZoneSet()
    {
        ini_set('date.timezone', 'EST');
        $tests = $this->_runner->getTests();
        $result = $tests[0]->run();
        $defTimeZoneMessage  = 'INI setting date.timezone is not set. EST set as the time zone.';
        $defTimeZoneMessage .= 'Please set date.timezone to you current time zone';
        $this->assertTrue(FALSE === strpos($result->getLog(), $defTimeZoneMessage), 'Default TZ warning present');
    }

    public function testWeCanRunEntireSuiteInOneCall()
    {
        $report = $this->_runner->runSuite();
        $this->assertFalse(empty($report), "Empty test report, why?");
    }
    
}
