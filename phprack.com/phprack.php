<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

global $phpRackConfig;
$phpRackConfig = array(
    'dir' => dirname(__FILE__) . '/../integration-tests',
);

include dirname(__FILE__) . '/../phpRack/bootstrap.php';
