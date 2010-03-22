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
 * @see phpRack_Runner_AuthResult
 */
require_once PHPRACK_PATH . '/Runner/AuthResult.php';

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
    
    public function testAuthenticationWorksProperly()
    {
        global $phpRackConfig;
        if (array_key_exists('auth', $phpRackConfig)) {
            $login = $phpRackConfig['auth']['username'];
            $hash = md5($phpRackConfig['auth']['password']);
        } elseif (array_key_exists('htaccess', $phpRackConfig)) {
            $fileContent = file($phpRackConfig['htaccess']);
            list($login, $hash) = explode(':', $fileContent[0], 2);
        } else {
            $login = $hash = ''; // no authentication
        }

        $auth = $this->_runner->authenticate($login, $hash, true);
        $this->assertTrue($auth instanceof phpRack_Runner_AuthResult);
        
        $this->assertTrue(
            $this->_runner->isAuthenticated(), 
            "User authentication not working properly. " .
            "login: '{$login}', hash: '{$hash}', message: '{$auth->getMessage()}'"
        );
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

    public function testWorksWhenDefaultTimeZoneNotSet()
    {
        ini_set('date.timezone', null);
        $tests = $this->_runner->getTests();
        $result = $tests[0]->run();
        $this->assertRegExp('/date\.timezone/', $result->getLog(), 'Default TZ warning missing');
    }

    public function testWorksWhenDefaultTimeZoneSet()
    {
        ini_set('date.timezone', 'EST');
        $tests = $this->_runner->getTests();
        $result = $tests[0]->run();
        $this->assertNotRegExp('/date\.timezone/', $result->getLog(), 'Default TZ warning exists, why?');
    }

    public function testWeCanRunEntireSuiteInOneCall()
    {
        $report = $this->_runner->runSuite();
        $this->assertFalse(empty($report), "Empty test report, why?");
    }
    
}
