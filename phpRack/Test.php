<?php
/**
 * phpRack: Integration Testing Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL: http://www.phprack.com/LICENSE.txt
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
 * @copyright Copyright (c) 2009-2012 phpRack.com
 * @version $Id$
 * @category phpRack
 * @package Tests
 * @subpackage core
 */

/**
 * @see phpRack_Runner
 */
require_once PHPRACK_PATH . '/Runner.php';

/**
 * @see phpRack_Assertion
 */
require_once PHPRACK_PATH . '/Assertion.php';

/**
 * Parent class of all integration tests
 *
 * @property-read phpRack_Assertion $assert One single test assertion
 * @package Tests
 * @subpackage core
 */
abstract class phpRack_Test
{

    /**
     * Success marker.
     */
    const OK = 'OK';

    /**
     * Failure marker.
     */
    const FAILURE = 'FAILURE';

    /**
     * This timezone will be used in there is NO timezone
     * set on the server.
     *
     * @see setUp()
     */
    const DEFAULT_TIMEZONE = 'EST';

    /**
     * ID of the test (unique in the system).
     *
     * @var string
     */
    protected $_fileName;

    /**
     * Runner of tests.
     *
     * @var phpRack_Runner
     */
    protected $_runner;

    /**
     * Assertion to use.
     *
     * @var phpRack_Assertion
     * @see __get()
     */
    protected $_assertion = null;

    /**
     * Ajax options to control front page behavior
     *
     * @var array
     * @see setAjaxOptions()
     * @see getAjaxOptions()
     */
    private $_ajaxOptions = array(
        'autoStart'          => true, // start it when front is loaded
        'reload'             => false, // reload every X seconds, in AJAX
        'linesCount'         => null, // how many lines should be displayed on browser side when we use tailf method
        'secVisible'         => null, // how long these lines should be visible (in seconds)
        'attachOutput'       => false, // attach output to previous result log
        'data'               => array(), // used for store data which should be returned in next ajax query
        'fileLastOffset'     => null, // used for control offset to read in phpRack_Package_Disc_File::tailf()
        'logSizeLimit'       => 100, // maximum size of log, in KB
        'pauseWhenFocusLost' => true, // stop ajax requests when window lost focus
        'tags'               => array(), // used for store available options for parametrized test
        'tag'                => null // selected tag param from front end
    );

    /**
     * Construct the class
     *
     * This constructor is going to be used only from factory() method. Making
     * this method "private" leads to problems in PHP 5.2.5 and maybe earlier
     * versions.
     *
     * @param $fileName string ID of the test, absolute (!) file name
     * @param $runner phpRack_Runner Instance of test runner
     * @return void
     * @see factory()
     */
    final protected function __construct($fileName, phpRack_Runner $runner)
    {
        $this->_fileName = realpath($fileName);
        $this->_runner = $runner;
        $this->_init();
    }

    /**
     * Create new instance of the class, using PHP absolute file name
     *
     * @param $label string ID of the test, its label
     * @param $runner phpRack_Runner Instance of test runner
     * @return phpRack_Test
     * @throws phpRack_Exception
     */
    public static function factory($label, phpRack_Runner $runner)
    {
        $fileName = $runner->getDir() . '/' . $label;
        if (!file_exists($fileName)) {
            throw new phpRack_Exception("File '{$fileName}' is not found");
        }
        if (!preg_match(phpRack_Runner::TEST_PATTERN, $fileName)) {
            throw new phpRack_Exception("File '{$fileName}' is not named properly, can't run it");
        }
        // fix for windows "\" path separator
        $fileName = preg_replace('/\\\\+/', '/', $fileName);
        // convert filename to class name, support also subdirs in tests dir
        $className = str_replace(
            '/', '_',
            substr($fileName, strlen($runner->getDir()) + 1, -4)
        );
        // workaround against ZCA static code analysis
        eval('require_once $fileName;');
        if (!class_exists($className)) {
            $match = array();
            // for back compatibility we should still support class names
            // without dir prefix in sub dirs
            if (preg_match('/_(\w+)$/', $className, $match)) {
                $className = $match[1];
            }
            if (!class_exists($className)) {
                throw new phpRack_Exception("Class '{$className}' is not defined in '{$fileName}'");
            }
        }

        return new $className($fileName, $runner);
    }

    /**
     * Dispatches property-like calls to the class
     *
     * @param $name string Name of the property to get
     * @return mixed
     * @throws phpRack_Exception If nothing found
     */
    final public function __get($name)
    {
        if ($name == 'assert') {
            if (!isset($this->_assertion)) {
                $this->_assertion = phpRack_Assertion::factory($this);
            }

            return $this->_assertion;
        }
        throw new phpRack_Exception("Property '{$name}' not found in " . get_class($this));
    }

    /**
     * Get unique test ID (file name of the test)
     *
     * @return string
     * @see $this->_fileName
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * Get label of the test
     *
     * @return string
     */
    public function getLabel()
    {
        return ltrim(substr($this->_fileName, strlen($this->_runner->getDir())), '/');
    }

    /**
     * Run the test and return result
     *
     * @return phpRack_Result
     * @see phpRack_Runner::run()
     */
    final public function run()
    {
        // clean all previous results, if any
        $this->assert->getResult()->clean();
        // find all methods that start with "test" and call them
        $rc = new ReflectionClass($this);
        foreach ($rc->getMethods() as $method) {
            if (!preg_match('/^test/', $method->getName())) {
                continue;
            }
            try {
                $this->setUp();
                // to avoid test cancelation because time is over
                set_time_limit(0);
                call_user_func(
                    array($this, $method->getName()),
                    $this->_ajaxOptions['tag']
                );
                $this->tearDown();
            } catch (Exception $e) {
                $this->assert->getResult()->addLog(
                    sprintf(
                        '[Exception] %s: %s "%s"',
                        $method->getName(),
                        get_class($e),
                        $e->getMessage()
                    )
                )
                ->fail();
            }
        }
        // add final log line, summarizing the test execution
        $this->assert->getResult()->addLog(
            sprintf(
                'Finished %s, %0.3fsec',
                get_class($this),
                $this->assert->getResult()->getDuration()
            )
        );
        // return instance of phpRack_Result class
        return $this->assert->getResult();
    }

    /**
     * Setup test environment, if necessary, before running every test
     *
     * @return void
     * @see run()
     */
    public function setUp()
    {
        // Check the default time zone
        $defaultTimeZone = ini_get('date.timezone');
        if (empty($defaultTimeZone)) {
            ini_set('date.timezone', self::DEFAULT_TIMEZONE);
            $this->_log(
                'INI setting date.timezone is not set. ' .
                self::DEFAULT_TIMEZONE . ' set as the time zone. ' .
                'Please set date.timezone to you current time zone'
            );
        }
    }

    /**
     * Clean environment if necessary
     *
     * @return void
     * @see run()
     */
    public function tearDown()
    {
    }

    /**
     * Set ajax options
     *
     * @param $options array List of options to set
     * @return void
     * @see phpRack_Package_Disc_File::tail()
     */
    public function setAjaxOptions($options)
    {
        foreach ($options as $name=>$value) {
            if (!array_key_exists($name, $this->_ajaxOptions)) {
                throw new phpRack_Exception("AJAX option '{$name}' is not valid");
            }
            $this->_ajaxOptions[$name] = $value;
        }
    }

    /**
     * Get ajax options
     *
     * @return array
     * @see phpRack_Runner::run()
     * @see index.phtml
     */
    public function getAjaxOptions()
    {
        return $this->_ajaxOptions;
    }

    /**
     * Simple assertion method to compare two values
     *
     * @param $dest mixed What we're expecting to have
     * @param $src mixed What we actually have
     * @param $message string Optional message to show
     * @return $this
     */
    public function assertEquals($dest, $src, $message = null)
    {
        if ($dest != $src) {
            if (!is_null($message)) {
                $this->_log($message);
            }
            $this->assert->fail("Comparison failed");
        } else {
            $this->_log("Comparison succeeded");
        }

        return $this;
    }

    /**
     * Allow child class to overwrite test default options, by overwritting this method
     * If you want disable ajax auto start it is proper place for that
     *
     * @return void
     * @see __construct()
     */
    protected function _init()
    {
    }

    /**
     * Log one message
     *
     * @param $message string The message
     * @param ... A number of parameters, passed to sprintf()
     * @return void
     */
    protected function _log($message/*, ...*/)
    {
        // pass it to sprintf
        if (func_num_args() > 1) {
            $args = func_get_args();
            $message = call_user_func_array(
                'sprintf',
                array_merge(
                    array($message),
                    array_slice($args, 1)
                )
            );
        }
        $this->assert->getResult()->addLog($message);
    }
}
