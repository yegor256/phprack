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
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * @see phpRack_Suite
 */
require_once PHPRACK_PATH . '/Suite.php';

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

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
 * @see bootstrap.php
 * @package Tests
 * @subpackage core
 */
class phpRack_Runner
{

    /**
     * This is how you should name your test files, if you want
     * them to be found by the Runner
     *
     * @var string
     * @see getTests()
     */
    const TEST_PATTERN = '/(\w+Test)\.php$/i';

    /**
     * This is how you should name your suite files, if you want
     * them to be found by the Runner
     *
     * @var string
     * @see getTests()
     */
    const SUITE_PATTERN = '/(\w+Suite)\.php$/i';

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
     * Authentication adapter
     *
     * @var phpRack_Runner_Auth
     */
    protected $_auth;

    /**
     * Construct the class
     *
     * @param $options array Options to set to the class
     * @return void
     * @throws phpRack_Exception If an option is invalid
     * @see bootstrap.php
     */
    public function __construct(array $options)
    {
        foreach ($options as $option=>$value) {
            if (!array_key_exists($option, $this->_options)) {
                throw new phpRack_Exception("Option '{$option}' is not recognized");
            }
            $this->_options[$option] = $value;
        }
        /**
         * @see phpRack_Runner_Auth
         */
        require_once PHPRACK_PATH . '/Runner/Auth.php';
        $this->_auth = new phpRack_Runner_Auth($this, $this->_options);
    }

    /**
     * Return authentication adapter
     *
     * @return phpRack_Runner_Auth
     * @see bootstrap.php
     */
    public function getAuth()
    {
        return $this->_auth;
    }

    /**
     * We're running the tests in CLI environment?
     *
     * @return boolean
     * @see phpRack_Runner_Auth::isAuthenticated()
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
     * @throws phpRack_Exception If directory is absent
     * @see getTests()
     */
    public function getDir()
    {
        $dir = $this->_options['dir'];
        if (!file_exists($dir)) {
            throw new phpRack_Exception("Test directory '{$dir}' is not found");
        }
        return realpath($dir);
    }

    /**
     * Get full list of tests, in array
     *
     * This method builds a list of phpRack_Test class instances, collecting
     * them from integration 1) tests and 2) suites. They both are located in
     * the same directory (pre-configured in $phpRackConfig), but differ only
     * in file name suffix. Integration test ends with "...Test.php" and integration
     * suite ends with "...Suite.php".
     *
     * Suite is an integration of tests, that allows you to use library tests
     * and suites. The majority of testing tasks are similar from server to server.
     * If you want to avoid manual development of tests for every application, just
     * use our library suites, and taylor them for your application needs.
     *
     * @return phpRack_Test[]
     * @see index.phtml
     */
    public function getTests()
    {
        $tests = array();
        $dir = $this->getDir();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
            $label = substr($file->getRealPath(), strlen($dir) + 1);
            switch (true) {
                case preg_match(self::TEST_PATTERN, $label):
                    $tests[] = phpRack_Test::factory($label, $this);
                    break;
                case preg_match(self::SUITE_PATTERN, $label):
                    $suite = phpRack_Suite::factory($label, $this);
                    foreach ($suite->getTests() as $test) {
                        $tests[] = $test;
                    }
                    break;
                default:
                    continue 2;
            }
        }
        return $tests;
    }

    /**
     * Create URL of a test that can be used outside of
     * phpRack front page to retrieve test results in JSON.
     *
     * @return string URL of the test
     */
    public function getTestURL($label)
    {
        return $_SERVER['PHP_SELF'] . '?' . PHPRACK_AJAX_TAG
            . '=' . urlencode($label);
    }

    /**
     * Run all tests and return a text report about their execution
     *
     * @return string
     * @see bootstrap.php
     */
    public function runSuite()
    {
        $tests = $this->getTests();
        $report = sprintf(
            "\nphpRack v%s\nSuite report, on %s\nPHPRACK_PATH: %s\n\n",
            PHPRACK_VERSION,
            date('d-M-y H:i:s'),
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
            } catch (phpRack_Exception $e) {
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
     * Run one test and return JSON result.
     *
     * @param $label string Test label
     * @param $token string Unique token to return back, if required
     * @param $options array Associative array of options to be used for setAjaxOptions()
     * @return string JSON
     * @throws phpRack_Exception
     * @see bootstrap.php
     */
    public function run($label, $token = 'token', $options = array())
    {
        if (!$this->getAuth()->isAuthenticated()) {
            throw new phpRack_Exception("Authentication failed, please login first");
        }
        // if this is one of our built-in tests
        if (!empty($options['suiteTest'])) {
            /**
             * @see phpRack_Suite
             */
            require_once PHPRACK_PATH . '/Suite/Test.php';
            $test = phpRack_Suite_Test::factory($label, $this);
            if (isset($options['config'])) {
                $test->setConfig($options['config']);
            }
        } else {
            $test = phpRack_Test::factory($label, $this);
        }
        unset($options['config']);
        unset($options['suiteTest']);
        $test->setAjaxOptions($options);
        $result = $test->run();
        $options = $test->getAjaxOptions();
        /**
         * @see phpRack_Runner_Logger
         */
        require_once PHPRACK_PATH . '/Runner/Logger.php';
        return json_encode(
            array(
                'success' => $result->wasSuccessful(),
                'options' => $options,
                'log' => phpRack_Runner_Logger::utf8Encode(
                    phpRack_Runner_Logger::cutLog(
                        $result->getLog(),
                        intval($options['logSizeLimit'])
                    )
                ),
                PHPRACK_AJAX_TOKEN => $token
            )
        );
    }

    /**
     * Notify admin about suite failure
     *
     * @param $report string Full suite text report
     * @return void
     * @see runSuite()
     * @throws phpRack_Exception
     * @todo #1 Now we work only with one notifier, which is in class phpRack_Mail. Later
     *  we should add other notifiers, like SMS, IRC, ICQ, etc. When we add them we
     *  should move our phpRack_Mail class to phpRack_Notifier_Mail and create other
     *  notifiers there.
     */
    protected function _notifyAboutFailure($report)
    {
        // no notification required
        if (empty($this->_options['notify'])) {
            return;
        }
        if (!is_array($this->_options['notify'])) {
            throw new phpRack_Exception("Parameter 'notify' should be an array, '{$this->_options['notify']}' given");
        }
        if (array_key_exists('email', $this->_options['notify'])) {
            /**
             * @see phpRack_Adapters_Notifier_Mail
             */
            require_once PHPRACK_PATH . '/Adapters/Notifier/Mail.php';

            if (array_key_exists('transport', $this->_options['notify']['email'])) {
                $transport = $this->_options['notify']['email']['transport'];
            } else {
                $transport = array();
            }
            if (!empty($transport['class'])) {
                $class = $transport['class'];
                unset($transport['class']);
            } else {
                $class = 'sendmail';
            }
            $mail = phpRack_Adapters_Notifier_Mail::factory($class, $transport);
            $mail->setSubject(
                'phpRack Suite Failure'
                . (isset($this->_options['notify']['title']) ?
                ' at ' . $this->_options['notify']['title'] : false)
            );
            $mail->setBody($report);
            /**
             * @todo #1 Only one recipient is supported now. Let's support more.
             */
            $mail->setTo($this->_options['notify']['email']['recipients']);
            $mail->send();
        }
    }

}
