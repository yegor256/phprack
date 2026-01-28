<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 */

/**
 * User custom suite
 *
 * @package Tests
 */

class MySuite extends phpRack_Suite
{
    /**
     * Set custom suites and tests
     */
    protected function _init()
    {
        $this->_addSuite('ServerHealth');
        $this->_addSuite(
            'DatabaseHealth',
            array(
                'url' => 'jdbc:mysql://localhost:3306/test?username=test&password=test'
            )
        );
        $this->_addSuite('Php5');
        $this->_addTest(
            'LogViewer',
            array(
                'file' => 'my.log',
            )
        );
    }
}
