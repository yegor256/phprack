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
 * @see phpRack_Runner_Auth_Result
 */
require_once PHPRACK_PATH . '/Runner/Auth/Result.php';

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
            $login = $hash = false; // no authentication
        }
        $auth = $this->_runner->getAuth()->authenticate($login, $hash, true);
        $this->assertTrue($auth instanceof phpRack_Runner_Auth_Result);
        $this->assertTrue(
            $auth->isValid(),
            "Invalid auth with authenticate(), " .
            "login: '{$login}', hash: '{$hash}', message: '{$auth->getMessage()}'"
        );
        $this->assertTrue(
            $this->_runner->getAuth()->isAuthenticated(),
            "Invalid result in isAuthenticated(), " .
            "login: '{$login}', hash: '{$hash}', message: '{$auth->getMessage()}'"
        );
    }
    
    public function testHeaderAuthenticationWorksProperly()
    {
        global $phpRackConfig;
        $authArray = array(
            'auth' => array(
                'username' => uniqid(),
                'password' => uniqid()
            )
        );
        // Injecting values into config
        $authArray = array_merge($phpRackConfig, $authArray);
        // Removing htaccess authentication in case it is set
        if (array_key_exists('htpasswd', $authArray)) {
            unset($authArray['htpasswd']);
        }
        // Creating instance of Runner to test it with our config
        $runner = new phpRack_Runner($authArray);
        $_SERVER['PHP_AUTH_USER'] = $authArray['auth']['username'];
        $_SERVER['PHP_AUTH_PW']   = $authArray['auth']['password'];
        $this->assertTrue(
            $runner->getAuth()->isAuthenticated(),
            'Invalid auth using header'
        );
    }
    
    public function testGetParamsAuthenticationWorksProperly()
    {
        global $phpRackConfig;
        $authArray = array(
            'auth' => array(
                'username' => uniqid(),
                'password' => uniqid()
            )
        );
        // Injecting values into config
        $authArray = array_merge($phpRackConfig, $authArray);
        
        // Removing htaccess authentication in case it is set
        if (array_key_exists('htpasswd', $authArray)) {
            unset($authArray['htpasswd']);
        }
        
        // Creating instance of Runner to test it with our config
        $runner = new phpRack_Runner($authArray);
        $_GET[phpRack_Runner_Auth::GET_LOGIN] = $authArray['auth']['username'];
        $_GET[phpRack_Runner_Auth::GET_PWD]   = $authArray['auth']['password'];
        $this->assertTrue(
            $runner->getAuth()->isAuthenticated(),
            'Invalid auth using get parameters (Phing bridge)'
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
        $this->assertNotNull($test, "Test is null, why?");
        $result = $test->run();
        $this->assertTrue($result instanceof phpRack_Result);
        $this->assertTrue(is_bool($result->wasSuccessful()));
        $this->assertTrue(is_string($result->getLog()));
    }

    public function testWorksWhenDefaultTimeZoneNotSet()
    {
        ini_set('date.timezone', null);
        $tests = $this->_runner->getTests();
        $this->assertTrue(is_array($tests));
        $this->assertTrue(count($tests) > 0, "No tests, why?");
        $result = $tests[0]->run();
        $this->assertRegExp('/date\.timezone/', $result->getLog(), 'Default TZ warning missing');
    }

    public function testWorksWhenDefaultTimeZoneSet()
    {
        ini_set('date.timezone', 'EST');
        $tests = $this->_runner->getTests();
        $this->assertTrue(is_array($tests));
        $this->assertTrue(count($tests) > 0, "No tests, why?");
        $result = $tests[0]->run();
        $this->assertNotRegExp('/date\.timezone/', $result->getLog(), 'Default TZ warning exists, why?');
    }

    public function testWeCanRunEntireSuiteInOneCall()
    {
        $report = $this->_runner->runSuite();
        $this->assertFalse(empty($report), "Empty test report, why?");
    }

    public function testRunnerCanBuildTestJsonUrl()
    {
        $_SERVER['PHP_SELF'] = '/hey/phprack.php';
        $url = $this->_runner->getTestURL('Hey/MyTest.php');
        $this->assertEquals(
            $url, '/hey/phprack.php?' . PHPRACK_AJAX_TAG . '=Hey%2FMyTest.php',
            'URL produced by getTestURL() is not correct'
        );
    }

}
