<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage core
 */

/**
 * To make sure that we're reporting ALL errors, and display them
 * all, no matter what are the settings of the server php.ini
 */
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('error_prepend_string', '');
ini_set('error_append_string', '');
ini_set('html_errors', false);
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
    function ($errno, $errstr, $errfile, $errline) {
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
    }
);
/**
 * Fix for IIS, see https://github.com/tpc2/phprack/issues/84.
 * I have no idea what it's for, but seems to be a necessary
 * fix for IIS.
 */
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 0);
    if (!empty($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}
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
        define('PHPRACK_VERSION', '2.0-SNAPSHOT');
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
    // if it's CLI environment - just show a full test report
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
        if (!$runner->getAuth()->isAuthenticated()) {
            throw new Exception('Access denied');
        }
        throw new Exception($runner->runSuite());
    }
    // Global layout is required, show the front web page of the report
    if (empty($_GET[PHPRACK_AJAX_TAG])) {
        $view = new phpRack_View();
        // show login form, if the user is not authenticated yet
        if (!$runner->getAuth()->isAuthenticated()) {
            $view->assign(array('authResult' => $runner->getAuth()->getAuthResult()));
            throw new Exception($view->render('login.phtml'));
        }
        $view->assign(array('runner' => $runner));
        /**
         * @todo #57 this line leads to the problem explained in the ticket,
         * on some servers, not everywhere. I don't know what is the reason, that's
         * why the line is commented for now.
         */
        // header('Content-Type: application/xhtml+xml');
        throw new Exception($view->render());
    }
    // show error message
    if (!$runner->getAuth()->isAuthenticated()) {
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
    $label = $options[PHPRACK_AJAX_TAG];
    unset($options[PHPRACK_AJAX_TAG]);
    $token = $options[PHPRACK_AJAX_TOKEN];
    unset($options[PHPRACK_AJAX_TOKEN]);

    header('Content-Type: application/json');
    throw new Exception($runner->run($label, $token, $options));
} catch (Exception $e) {
    /**
     * Here we render the content prepared above. It's not
     * an exception actually, but a content prepared. Such
     * design is not perfect and needs refactoring sooner or
     * later...
     */
    echo $e->getMessage();
}
