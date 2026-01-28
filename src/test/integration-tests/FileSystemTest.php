<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class FileSystemTest extends phpRack_Test
{

    public function testWeHaveEnoughFreeSpace()
    {
        $this->assert->disc->freeSpace
            ->atLeast(100);
    }

    public function testShowDirectoryWorks()
    {
        $this->assert->disc->showDirectory(
            '.',
            array(
                // exclude all files the match this regex
                'exclude' => array(
                    '/\.svn\//'
                ),
                // show only two levels down
                'maxDepth' => 2,
            )
        );
    }

}
