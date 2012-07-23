<?php
/**
 * @version $Id$
 */

global $phpRackConfig;
$phpRackConfig = array(
    'auth' => array(
        'username' => 'phprack',
        'password' => 'phprack',
    ),
    'dir' => dirname(__FILE__) . '/../integration-tests',
);

include dirname(__FILE__) . '/../../phpRack/bootstrap.php';
