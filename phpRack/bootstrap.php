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

try {

    // This variable ($phpRackConfig) shall be declared and filled with
    // values in your phprack.php file, which calls this bootstraper. For
    // complete reference on this variable see:
    // http://trac.fazend.com/phpRack/wiki/Bootstrap
    if (!isset($phpRackConfig)) {
        throw new Exception('Invalid configuration: $phpRackConfig is missed');
    }

    defined('PHPRACK_VERSION') or
        define('PHPRACK_VERSION', '0.1');

    defined('PHPRACK_AJAX_TAG') or
        define('PHPRACK_AJAX_TAG', 'test');

    defined('PHPRACK_AJAX_TOKEN') or
        define('PHPRACK_AJAX_TOKEN', 'token');

    defined('PHPRACK_PATH') or
        define('PHPRACK_PATH', dirname(__FILE__));

    require_once PHPRACK_PATH . '/Runner.php';
    $runner = new PhpRack_Runner($phpRackConfig);
    
    // Global layout is required
    if (empty($_GET[PHPRACK_AJAX_TAG])) {
        require_once PHPRACK_PATH . '/View.php';
        $view = new PhpRack_View($runner);
        echo $view->render();
    } else {
        echo $runner->run($_GET[PHPRACK_AJAX_TAG], $_GET[PHPRACK_AJAX_TOKEN]);
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
