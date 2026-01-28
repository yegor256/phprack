<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
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
