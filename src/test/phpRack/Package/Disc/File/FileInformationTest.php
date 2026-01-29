<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Package_Disc_File_TestCase
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Disc/File/TestCase.php';

class phpRack_Package_Disc_File_FileInformationTest extends phpRack_Package_Disc_File_TestCase
{
    public function testExists()
    {
        $this->_package->exists($this->_testFilesDir . '/5lines.txt');
        $this->assertTrue($this->_result->wasSuccessful());

        $this->_package->exists($this->_testFilesDir . '/notexists.txt');
        $this->assertFalse($this->_result->wasSuccessful());
    }

    public function testIsReadable()
    {
        $fileName = tempnam('/tmp', 'test_');
        $this->_tmpFileName = $fileName;

        chmod($fileName, 0400); // Set read permission
        $this->_package->isReadable($fileName);
        $this->assertTrue($this->_result->wasSuccessful());
    }

    public function testIsWritable()
    {
        $fileName = tempnam('/tmp', 'test_');
        $this->_tmpFileName = $fileName;

        chmod($fileName, 0200); // Set write permission
        $this->_package->isWritable($fileName);
        $this->assertTrue($this->_result->wasSuccessful());
    }

    public function testIsDir()
    {
        $this->_package->isDir($this->_testFilesDir);
        $this->assertTrue($this->_result->wasSuccessful());

        $this->_package->isDir($this->_testFilesDir . '/5lines.txt');
        $this->assertFalse($this->_result->wasSuccessful());
    }

    public function testWithNotExistingFile()
    {
        $fileName = $this->_testFilesDir . '/notexists.txt';

        $this->_package->isReadable($fileName);
        $this->assertFalse($this->_result->wasSuccessful());

        $this->_package->isWritable($fileName);
        $this->assertFalse($this->_result->wasSuccessful());

        $this->_package->isDir($fileName);
        $this->assertFalse($this->_result->wasSuccessful());
    }
}
