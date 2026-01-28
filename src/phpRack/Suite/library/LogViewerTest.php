<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage suites
 */

/**
 * @see phpRack_Suite_Test
 */
require_once PHPRACK_PATH . '/Suite/Test.php';

/**
 * View log file.
 *
 * @package Tests
 * @subpackage suites
 */
class phpRack_Suite_LogViewerTest extends phpRack_Suite_Test
{
    /**
     * Configuration options
     *
     * @var array
     */
    protected $_config = array(
        'file' => 'php://temp',
    );

    /**
     * View log file in AJAX mode
     *
     * @return void
     */
    public function testShowLogFile()
    {
        $this->assert->disc->file
            ->tailf($this->_config['file']);
    }
}
