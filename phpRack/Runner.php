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
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
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
 * @see bootstrap.php
 */
class phpRack_Runner
{

    /**
     * COOKIE name
     *
     * @see isAuthenticated()
     */
    const COOKIE_NAME = 'phpRack_auth';

    /**
     * COOKIE lifetime in seconds
     *
     * We set to 30 days, which equals to 30 * 24 * 60 * 60 = 2592000
     *
     * @see isAuthenticated()
     */
    const COOKIE_LIFETIME = 2592000;

    /**
     * Form param names
     *
     * @see isAuthenticated()
     */
    const POST_LOGIN = 'login';
    const POST_PWD = 'password';

    /**
     * Param names for authenticating using GET
     *
     * @see isAuthenticated()
     */
    const GET_LOGIN = 'login';
    const GET_PWD = 'password';

    /**
     * This is how you should name your test files, if you want
     * them to be found by the Runner
     *
     * @var string
     * @see getTests()
     */
    const TEST_PATTERN = '/(\w+Test)\.php$/i';

    /**
     * List of options, which are changeable
     *
     * @var array
     * @see __construct()
     */
    protected $_options = array(
        'dir'      => null,
        'auth'     => null,
        'htpasswd' => null,
        'notify'   => null,
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
     * @see bootstrap.php
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
     * @param boolean Defines whether second argument is password or it's hash
     * @return phpRack_Runner_AuthResult
     * @see bootstrap.php
     */
    public function authenticate($login, $password, $isHash = false)
    {
        // if it's already authenticated, just return it
        if (!is_null($this->_authResult)) {
            return $this->_authResult;
        }

        // make sure that we're working with HASH
        $hash = ($isHash) ? $password : md5($password);

        switch (true) {
            // plain authentication by login/password
            // this option is set by default to NULL, here we validate that
            // it was changed to ARRAY
            case is_array($this->_options['auth']):
                $auth = $this->_options['auth'];
                if ($auth['username'] != $login) {
                    return $this->_validated(false, 'Invalid login');
                }
                if (md5($auth['password']) != $hash) {
                    return $this->_validated(false, 'Invalid password');
                }
                return $this->_validated(true);

            // list of login/password provided in file
            // this option is set by default to NULL, here we just validate
            // that it contains a name of file
            case is_string($this->_options['htpasswd']):
                require_once PHPRACK_PATH . '/Adapters/File.php';
                $file = phpRack_Adapters_File::factory($this->_options['htpasswd'])->getFileName();

                $fileContent = file($file);
                foreach ($fileContent as $line) {
                    list($lg, $psw) = explode(':', $line, 2);
                    /* Just to make sure we don't analyze some whitespace */
                    $lg = trim($lg);
                    $psw = trim($psw);
                    if (($lg == $login) && ($psw == $hash)) {
                        return $this->_validated(true);
                    }
                }
                return $this->_validated(false, 'Invalid login credentials provided');

            // authenticated TRUE, if no authentication required
            default:
                return $this->_validated(true);
        }
    }

    /**
     * Checks whether user is authenticated before running any tests
     *
     * @return boolean
     * @see bootstrap.php
     */
    public function isAuthenticated()
    {
        if (!is_null($this->_authResult)) {
            return $this->_authResult->isValid();
        }

        // global variables, in case they are not declared as global yet
        global $_COOKIE;

        // there are a number of possible authentication scenarios
        switch (true) {
            // login/password are provided in HTTP request, through POST
            // params. we should save them in COOKIE in order to avoid
            // further login requests.
            case array_key_exists(self::POST_LOGIN, $_POST) &&
            array_key_exists(self::POST_PWD, $_POST):
                $login = $_POST[self::POST_LOGIN];
                $hash = md5($_POST[self::POST_PWD]);
                setcookie(
                    self::COOKIE_NAME, // name of HTTP cookie
                    $login . ':' . $hash, // hashed form of login and pwd
                    time() + self::COOKIE_LIFETIME // cookie expiration date
                );
                break;

            // login/password are provided as GET params
            // as it's only one-time Phing bridge,
            // we don't store them anywhere
            case array_key_exists(self::GET_LOGIN, $_GET) &&
            array_key_exists(self::GET_PWD, $_GET):
                $login = $_GET[self::GET_LOGIN];
                $hash = md5($_GET[self::GET_PWD]);
                break;

            // this is CLI environment, not web -- we don't require any
            // authentication
            case $this->isCliEnvironment():
                return $this->_validated(true)->isValid();

            // we already have authentication information in COOKIE, we just
            // need to parse it and validate
            case array_key_exists(self::COOKIE_NAME, $_COOKIE):
                list($login, $hash) = explode(':', $_COOKIE[self::COOKIE_NAME]);
                break;

            // we expect authentication information to be sent via headers
            // for example by Phing
            case array_key_exists('PHP_AUTH_USER', $_SERVER) &&
            array_key_exists('PHP_AUTH_PW', $_SERVER):
                $login = $_SERVER['PHP_AUTH_USER'];
                $hash = md5($_SERVER['PHP_AUTH_PW']);
                break;

            // no authinfo, chances are that site is not protected
            default:
                $login = $hash = false;
                break;
        }

        return $this->authenticate($login, $hash, true)->isValid();
    }

    /**
     * Get current auth result, if it exists
     *
     * @return phpRack_Runner_AuthResult
     * @see boostrap.php
     * @throws Exception If the result is not set yet
     */
    public function getAuthResult()
    {
        if (!isset($this->_authResult)) {
            throw new Exception("AuthResult is not set yet, use authenticate() before");
        }
        return $this->_authResult;
    }

    /**
     * We're running the tests in CLI environment?
     *
     * @return boolean
     * @see isAuthenticated()
     */
    public function isCliEnvironment()
    {
        global $_SERVER;
        return empty($_SERVER['DOCUMENT_ROOT']);
    }

    /**
     * Check whether client connection has enough security level?
     *
     * @return boolean
     * @see bootstrap.php
     */
    public function isEnoughSecurityLevel()
    {
        global $_SERVER;
        if (empty($this->_options['auth']['onlySSL'])) {
            return true;
        }
        return !empty($_SERVER['HTTPS']);
    }

    /**
     * Get tests location directory
     *
     * @return string
     * @throws Exception If directory is absent
     * @see getTests()
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
     * @see index.phtml
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
     * @see boostrap.php
     */
    public function runSuite()
    {
        $tests = $this->getTests();
        $report = sprintf(
            "phpRack v%s suite report, on %s\nPHPRACK_PATH: %s\n",
            date('m/d/y h:i:s'),
            PHPRACK_VERSION,
            PHPRACK_PATH
        );
        $success = true;
        $duration = 0;
        foreach ($tests as $test) {
            $result = $test->run();
            $report .= sprintf(
                "%s\n%s: %s, %0.3fsec\n\n",
                $result->getPureLog(),
                $test->getLabel(),
                $result->wasSuccessful() ? phpRack_Test::OK : phpRack_Test::FAILURE,
                $result->getDuration()
            );
            $success &= $result->wasSuccessful();
            $duration += $result->getDuration();
        }
        $report .= sprintf(
            "PHPRACK SUITE: %s, %0.2fmin\n",
            $success ? phpRack_Test::OK : phpRack_Test::FAILURE,
            $duration / 60
        );

        // notify about suite failure
        if (!$success) {
            try {
                $this->_notifyAboutFailure($report);
            } catch (Exception $e) {
                $report .= sprintf(
                    "Failed to notify admin (%s): '%s'\n",
                    get_class($e),
                    $e->getMessage()
                );
            }
        }

        return $report;
    }

    /**
     * Run one test and return JSON result
     *
     * @param string Test file name (absolute name of PHP file)
     * @param string Unique token to return back, if required
     * @param array Associative array of options to be used for setAjaxOptions()
     * @return string JSON
     * @throws Exception
     * @see bootstrap.php
     */
    public function run($fileName, $token = 'token', $options = array())
    {
        if (!$this->isAuthenticated()) {
            //TODO: handle situation when login screen should appear
            throw new Exception("Authentication failed, please login first");
        }
        $test = phpRack_Test::factory($fileName, $this);
        $test->setAjaxOptions($options);

        $result = $test->run();
        return json_encode(
            array(
                'success' => $result->wasSuccessful(),
                'log' => utf8_encode($result->getLog()),
                PHPRACK_AJAX_TOKEN => $token,
                'options' => $test->getAjaxOptions()
            )
        );
    }

    /**
     * Notify admin about suite failure
     *
     * @param string Full suite text report
     * @return void
     * @see runSuite()
     * @throws Exception
     * @todo Now we work only with one notifier, which is in class phpRack_Mail. Later
     *      we should add other notifiers, like SMS, IRC, ICQ, etc. When we add them we 
     *      should move our phpRack_Mail class to phpRack_Notifier_Mail and create other
     *      notifiers there.
     */
    protected function _notifyAboutFailure($report) 
    {
        // no notification required
        if (empty($this->_options['notify'])) {
            return;
        }
        
        if (!is_array($this->_options['notify'])) {
            throw new Exception("Parameter 'notify' should be an array, '{$this->_options['notify']}' given");
        }
        
        if (array_key_exists('email', $this->_options['notify'])) {
            /**
             * @see phpRack_Adapters_Notifier_Mail
             */
            require_once PHPRACK_PATH . '/Adapters/Notifier/Mail.php';

            $transport = $this->_options['notify']['email']['transport'];
            if (!empty($transport['class'])) {
                $class = $transport['class'];
                unset($transport['class']);
            } else {
                $class = 'sendmail';
            }
            $mail = phpRack_Adapters_Notifier_Mail::factory($class, $transport);
            $mail->setSubject('phpRack Suite Failure');
            $mail->setBody($report);
            /**
             * @todo Only one recipient is supported now
             */
            $mail->setTo($this->_options['notify']['email']['recipients']);
            $mail->send();
        }
    }

    /**
     * Save and return an AuthResult
     *
     * @param boolean Success/failure of the validation
     * @param string Optional error message
     * @return phpRack_Runner_AuthResult
     * @see authenticate()
     */
    protected function _validated($result, $message = null)
    {
        return $this->_authResult = new phpRack_Runner_AuthResult($result, $message);
    }

}
