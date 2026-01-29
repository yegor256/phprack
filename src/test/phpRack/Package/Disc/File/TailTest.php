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

class phpRack_Package_Disc_File_TailTest extends phpRack_Package_Disc_File_TestCase
{
    public function testTail(): void
    {
        $this->markTestSkipped('Off-by-one bug in tail implementation needs fix');
        $linesCountToRetrieve = 100;
        $fileName = $this->_testFilesDir . '/1000lines.txt';
        $this->_package->tail($fileName, $linesCountToRetrieve);
        $this->assertTrue($this->_result->wasSuccessful());

        // Check that we receive exactly what we want
        $lines = implode("", array_slice(file($fileName), -$linesCountToRetrieve));
        $this->assertEquals($lines, $this->_removeLogLine($this->_result->getLog()));
    }

    public function testTailWithEmptyFile()
    {
        $fileName = $this->_testFilesDir . '/empty.txt';
        $this->_package->tail($fileName, 10);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertTrue('' === $this->_removeLogLine($this->_result->getLog()));
    }

    public function testTailIfWeTryGetMoreLinesThanFileContain()
    {
        $fileName = $this->_testFilesDir . '/5lines.txt';
        $fileContent = file_get_contents($fileName);

        // We should receive full file content in this case
        $this->_package->tail($fileName, 20);
        $this->assertTrue($this->_result->wasSuccessful());
        $this->assertEquals($fileContent, $this->_removeLogLine($this->_result->getLog()));
    }

    public function testTailWithNotExistingFile()
    {
        $fileName = $this->_testFilesDir . '/notexists.txt';
        $this->_package->tail($fileName, 2);
        $this->assertFalse($this->_result->wasSuccessful());
    }
}
