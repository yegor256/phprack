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
 * List of processes running on the server.
 *
 * @package Tests
 * @subpackage suites
 */
class phpRack_Suite_ServerHealth_TopProcessesTest extends phpRack_Suite_Test
{
    /**
     * Pre-configuration of the test
     *
     * @return void
     */
    protected function _init()
    {
        $this->setAjaxOptions(
            array(
                'reload' => 5, // every 5 seconds, if possible
            )
        );
    }

    /**
     * Show full list of top-processes and some other supplementary information
     *
     * @return void
     * @todo #48 This test works only on Linux, so we should change it
     *      soon to something more portable
     */
    public function testShowProcesses()
    {
        $this->assert->shell->exec('date 2>&1');
        $this->assert->shell->exec('uptime 2>&1');
        $this->assert->shell->exec(
            'ps o "%cpu %mem nice user time stat command" ax | '
            . 'awk \'NR==1; NR > 1 {print $0 | "sort -k 1 -r"}\' | '
            . 'grep -v "^ 0.0" 2>&1'
        );
        $this->assert->shell->exec('df 2>&1');
    }
}
