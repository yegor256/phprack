<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class Disc_FileTest extends phpRack_Test
{
    public function testFileContent()
    {
        $fileName = '../test/phpRack/Package/Disc/_files/5lines.txt';

        // Show the content of the file
        $this->assert->disc->file->cat($fileName);

        // Show head of the file, 2 first lines
        $this->assert->disc->file->head($fileName, 2);

        // Show tail of the file, 2 last lines
        $this->assert->disc->file->tail($fileName, 2);
    }

    public function testFile()
    {
        $fileName = '../test/phpRack/Package/Disc/_files/5lines.txt';
        $this->assert->disc->file
            ->exists($fileName) // this file exists
            ->isReadable($fileName) // it is readable
            ->isWritable($fileName); // it is writable

        $fileName = '../test/phpRack/Package/Disc/_files';
        $this->assert->disc->file->isDir($fileName); // it is a directory
    }
}
