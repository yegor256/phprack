<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
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
 * Check database status
 *
 * @package Tests
 */
class phpRack_Suite_DatabaseHealth_DbTest extends phpRack_Suite_Test
{
    /**
     * Configuration options
     *
     * @var array
     * @see setConfig()
     */
    protected $_config = array(
        'url' => null
    );

    /**
     * Check db status
     *
     * @todo #48: Should be implemented (need add jdbc parameter support in
     * db package)
     * @return void
     */
    public function testDb()
    {
        $this->_log('Passed jdbc url: ' . $this->_config['url']);
    }
}
