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
 * @copyright Copyright (c) phpRack.com
 * @version $Id$
 * @category phpRack
 */

// Here we define a error handler in order to catch all possible
// PHP errors and show them online, no matter what server settings
// exist for error handling...
set_error_handler(
    create_function(
        '$errno, $errstr, $errfile, $errline',
        '
        echo sprintf(
            "phpRack error (%s): %s, in %s [line:%d]",
            $errno,
            $errstr,
            $errfile,
            $errline
        );
        '
    )
);

try {
    // This variable ($phpRackConfig) shall be declared and filled with
    // values in your phprack.php file, which calls this bootstraper. For
    // complete reference on this variable see:
    // http://trac.fazend.com/phpRack/wiki/Bootstrap
    global $phpRackConfig;
    if (!isset($phpRackConfig)) {
        throw new Exception('Invalid configuration: $phpRackConfig is missed');
    }
    
    if (!defined('PHPRACK_VERSION')) {
        define('PHPRACK_VERSION', '0.1dev');
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
    
    if (!$runner->isAuthenticated()) {
        require_once PHPRACK_PATH . '/View.php';
        $view = new phpRack_View();
        $view->assign(array('runner' => $runner));
        echo $view->render('login.phtml');
    }
    
    // Global layout is required
    if (empty($_GET[PHPRACK_AJAX_TAG])) {
        /**
         * @see phpRack_View
         */
        require_once PHPRACK_PATH . '/View.php';
        $view = new phpRack_View(); 
        $view->assign(array('runner' => $runner)); 
        echo $view->render();
    } else {
        // Execute one individual test and return its result
        // in JSON format. We reach this point only in AJAX calls from
        // already rendered testing page.
        echo $runner->run($_GET[PHPRACK_AJAX_TAG], $_GET[PHPRACK_AJAX_TOKEN]);
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
