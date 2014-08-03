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
 * @subpackage suites
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * Parent class of all tests in suite library.
 *
 * @package Tests
 * @subpackage suites
 */
abstract class phpRack_Suite_Test extends phpRack_Test
{
    /**
     * Configuration options
     *
     * @var array
     * @see setConfig()
     */
    protected $_config = array();

    /**
     * Set configuration params
     *
     * @param array List of configuration options
     * @return void
     * @throws phpRack_Exception
     */
    final public function setConfig(array $config = array())
    {
        foreach ($config as $name=>$value) {
            if (!array_key_exists($name, $this->_config)) {
                throw new phpRack_Exception("Option '{$name}' is not allowed for " . get_class($this));
            }
            $this->_config[$name] = $value;
        }

        /**
         * set ajax option with config values, because we need to receive these
         * informations back from front end
         */
        $this->setAjaxOptions(
            array(
                'data' => array(
                    'config' => $this->_config
                )
            )
        );
    }

    /**
     * Create new instance of the class, using PHP absolute file name
     *
     * @param $label string ID of the test, its label
     * @param $runner phpRack_Runner Instance of test runner
     * @return phpRack_Suite_Test
     * @throws phpRack_Exception
     */
    public static function factory($label, phpRack_Runner $runner)
    {
        $fileName = PHPRACK_PATH . '/Suite/library/' . $label;
        if (!file_exists($fileName)) {
            throw new phpRack_Exception("File '{$fileName}' is not found");
        }
        if (!preg_match(phpRack_Runner::TEST_PATTERN, $fileName)) {
            throw new phpRack_Exception("File '{$fileName}' is not named properly, can't run it");
        }
        // workaround against ZCA static code analysis
        eval('require_once $fileName;');
        // fix for Windows
        $fileName = preg_replace('/\\\\+/', '/', $fileName);
        // extract relative part of path
        $path = substr(
            $fileName,
            strlen(PHPRACK_PATH) + strlen('/Suite/library/'),
            -strlen('.php')
        );
        // convert path to class name
        $className = 'phpRack_Suite_' . preg_replace('/\/+/', '_', $path);
        if (!class_exists($className)) {
            throw new phpRack_Exception("Class '{$className}' is not defined in '{$fileName}'");
        }

        return new $className($fileName, $runner);
    }

    /**
     * Get tests label.
     * @return string
     */
    public function getLabel()
    {
        return ltrim(substr($this->_fileName, strlen(PHPRACK_PATH . '/Suite/library/')), '/');
    }
}
