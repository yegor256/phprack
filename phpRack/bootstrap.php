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
 * To make sure that we're reporting ALL errors, and display them
 * all, no matter what are the settings of the server php.ini
 */
error_reporting(E_ALL);
ini_set('display_errors', true);

/**
 * Here we define a error handler in order to catch all possible
 * PHP errors and show them online, no matter what server settings
 * exist for error handling...
 *
 * Warnings will be IGNORED if statement that caused the error
 * was prepended by the @ error-control operator. This behavior
 * is important for functions like mysql_connect(), fsockopen()
 * to avoid warnings displaying, because when we use them we
 * implement own error detection mechanism.
 */
set_error_handler(
    create_function(
        '$errno, $errstr, $errfile, $errline',
        '
        if (in_array($errno, array(E_WARNING)) && error_reporting() == 0) {
            return;
        }
        echo sprintf(
            "phpRack error (%s): %s, in %s [line:%d]\n",
            $errno,
            $errstr,
            $errfile,
            $errline
        );
        '
    )
);

try {
    /**
     * This variable ($phpRackConfig) shall be declared and filled with
     * values in your phprack.php file, which calls this bootstraper. For
     * complete reference on this variable see:
     * @see http://trac.fazend.com/phpRack/wiki/Bootstrap
     */
    global $phpRackConfig;
    if (!isset($phpRackConfig)) {
        throw new Exception('Invalid configuration: global $phpRackConfig is missed');
    }
    
    if (!defined('PHPRACK_VERSION')) {
        // we use svn:keywords here in order to get the revision number of phpRack
        $revision = intval(substr('$Rev$', 6));
        define('PHPRACK_VERSION', '0.1dev' . ($revision ? " (r{$revision})" : false));
    }

    if (!defined('PHPRACK_AJAX_TAG')) {
        define('PHPRACK_AJAX_TAG', 'test');
    }

    if (!defined('PHPRACK_AJAX_TOKEN')) {
        define('PHPRACK_AJAX_TOKEN', 'token');
    }

    if (!defined('PHPRACK_PATH')) {
        define('PHPRACK_PATH', dirname(__FILE__));
    }

    /**
     * @see phpRack_Runner
     */
    require_once PHPRACK_PATH . '/Runner.php';
    $runner = new phpRack_Runner($phpRackConfig);
    
    /**
     * @see phpRack_View
     */
    require_once PHPRACK_PATH . '/View.php';

    // if it's CLI enviroment - just show a full test report
    if ($runner->isCliEnvironment()) {
        throw new Exception($runner->runSuite());
    }

    // check whether SSL connection is mandatory?
    if (!$runner->isEnoughSecurityLevel()) {
        throw new Exception('You must use SSL protocol to run integration tests');
    }
    
    /**
     * Using this tag in GET URL we can get a summary report
     * in plain text format.
     */
    if (array_key_exists('suite', $_GET)) {
        header('Content-Type: text/plain');
        if (!$runner->isAuthenticated()) {
            throw new Exception('Access denied');
        }
        throw new Exception($runner->runSuite());
    }

    // Global layout is required, show the front web page of the report
    if (empty($_GET[PHPRACK_AJAX_TAG])) {
        $view = new phpRack_View();
        // show login form, if the user is not authenticated yet
        if (!$runner->isAuthenticated()) {
            $view->assign(array('authResult' => $runner->getAuthResult()));
            throw new Exception($view->render('login.phtml'));
        }
        $view->assign(array('runner' => $runner)); 
        header('Content-Type: application/xhtml+xml');
        throw new Exception($view->render());
    }

    // show error message
    if (!$runner->isAuthenticated()) {
        throw new Exception("Authentication problem. You have to login first.");
    }

    /**
     * Execute one individual test and return its result
     * in JSON format. We reach this point only in AJAX calls from
     * already rendered testing page.
     */
    $options = $_GET;
    
    /** 
     * '_' param is automatically added by jQuery with current time in miliseconds,
     * when we call $.ajax function with cache = false. We unset it to have
     * no exception in phpRack_Test::setAjaxOptions()
     */
    unset($options['_']);
    
    $fileName = $options[PHPRACK_AJAX_TAG];
    unset($options[PHPRACK_AJAX_TAG]);
    $token = $options[PHPRACK_AJAX_TOKEN];
    unset($options[PHPRACK_AJAX_TOKEN]);

    header('Content-Type: application/json');
    throw new Exception($runner->run($fileName, $token, $options));

} catch (Exception $e) {
    echo $e->getMessage();
}
