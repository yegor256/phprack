<?php

define('PHPRACK_PATH', realpath(dirname(__FILE__) . '/../phpRack'));

global $phpRackConfig;
$phpRackConfig = array(
    'dir' => dirname(__FILE__) . '/integration-tests',
);

// These variables are normally set in bootstrap.php
// but here we should set them explicitly, for tests only
$_SERVER['REQUEST_URI'] = 'no-URL-it-is-testing.com';
define('PHPRACK_AJAX_TAG', 'testing-tag');
define('PHPRACK_AJAX_TOKEN', 'testing-token');

require_once 'PHPUnit/Framework/TestCase.php';

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    
}
