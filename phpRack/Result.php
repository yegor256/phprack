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
 * Result of a test execution
 *
 * @package Tests
 * @see phpRack_Assertion::__construct()
 */
class phpRack_Result
{

    /**
     * Log lines
     *
     * @var string
     * @see getLog()
     */
    protected $_lines;

    /**
     * Total result is SUCCESS?
     *
     * @var boolean
     * @see wasSuccessful()
     */
    protected $_success;

    /**
     * When this result was created by test
     *
     * @var float
     * @see clean()
     * @see getDuration()
     */
    protected $_started;

    /**
     * Test object which is owner of this result object,
     * used for give ability to set ajax options from phpRack_Package
     *
     * @var phpRack_Test
     * @see setTest()
     * @see getTest()
     */
    protected $_test;

    /**
     * Construct the class
     *
     * @param phpRack_Test Test, which pushes results here
     * @return void
     * @see phpRack_Assertion::__construct()
     */
    public function __construct(phpRack_Test $test)
    {
        $this->_test = $test;
        $this->clean();
    }

    /**
     * Set total result to FAILURE
     *
     * @return void
     * @see phpRack_Package::_failure()
     */
    public function fail()
    {
        $this->_success = false;
    }

    /**
     * Was the test successful?
     *
     * @return boolean
     * @see phpRack_Runner::runSuite()
     */
    public function wasSuccessful()
    {
        return $this->_success;
    }

    /**
     * Get full log of the result
     *
     * @return string
     * @see phpRack_Runner::run()
     */
    public function getLog()
    {
        return implode("\n", $this->_lines);
    }

    /**
     * Get log of assertions only, without any other messages
     *
     * @return string
     * @see phpRack_Runner::runSuite()
     */
    public function getPureLog()
    {
        return implode("\n", preg_grep('/^\[[A-Z]+\]\s/', $this->_lines));
    }

    /**
     * Get result lifetime, duration in seconds
     *
     * @return void
     * @see phpRack_Runner::runSuite()
     */
    public function getDuration()
    {
        return microtime(true) - $this->_started;
    }

    /**
     * Add new log line
     *
     * @param string Log line to add
     * @return $this
     * @see phpRack_Package::_log()
     */
    public function addLog($line)
    {
        $options = $this->_test->getAjaxOptions();
        if (!empty($options['logSizeLimit'])
            && is_numeric($options['logSizeLimit'])) {
            $len = 0;
            if (function_exists('mb_strlen')) {
                $len += mb_strlen($line, 'UTF-8');
            } elseif (function_exists('iconv_strlen')) {
                $len += iconv_strlen($line, 'UTF-8');
            } else {
                $len += strlen($line) * 2; // bad variant
            }

            $max = $options['logSizeLimit'] * 1024;
            if ($len > $max) {
                $cutSize = $max / 2;
                $func = '';
                if (function_exists('iconv_substr')) {
                    $func = 'iconv_substr';
                } elseif (function_exists('mb_substr')) {
                    $func = 'mb_substr';
                }
                if ($func) {
                    $head = call_user_func($func, $line, 0, $cutSize, 'UTF-8');
                    $tail = call_user_func(
                        $func, $line, -1 * $cutSize, $cutSize, 'UTF-8'
                    );
                } else {
                    // bad variant
                    $head = substr($line, 0, $cutSize * 2);
                    $tail = substr($line, -1 * $cutSize * 2);
                }
                $line = "{$head}\n\n\t" . str_repeat('.', 50) . "\n\n{$tail}";
            }
        }

        $this->_lines[] = $line;
        return $this;
    }

    /**
     * Clean log
     *
     * @return void
     * @see phpRack_Test::run()
     */
    public function clean()
    {
        $this->_success = true;
        $this->_lines = array();
        $this->_started = microtime(true);
    }

    /**
     * Get test which is owner of this result object
     *
     * @return phpRack_Test
     * @see phpRack_Package_Disc_File::tail()
     */
    public function getTest()
    {
        return $this->_test;
    }
}
