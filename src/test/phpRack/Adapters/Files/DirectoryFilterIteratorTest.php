<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Files_DirectoryFilterIterator
 */
require_once PHPRACK_PATH . '/Adapters/Files/DirectoryFilterIterator.php';

class phpRack_Adapters_Files_DirectoryFilterIteratorTest extends AbstractTest
{
    public function testWeCanIterateThroughFiles()
    {
        $iterator = phpRack_Adapters_Files_DirectoryFilterIterator::factory(PHPRACK_PATH);
        $iterator
            ->setExtensions('php, phtml')
            ->setExclude('/\.svn/')
            ->setMaxDepth(0);
        $this->assertGreaterThan(0, iterator_count($iterator), 'Empty iterator, why?');
    }
}
