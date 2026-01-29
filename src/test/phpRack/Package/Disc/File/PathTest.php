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
 * @see phpRack_Package_Disc_File_AbstractTest
 */
require_once PHPRACK_PATH . '/../test/phpRack/Package/Disc/File/AbstractTest.php';

class phpRack_Package_Disc_File_PathTest extends phpRack_Package_Disc_File_AbstractTest
{
    public function testRelativePaths()
    {
        $fileName = 'test/phpRack/Package/Disc/_files/5lines.txt';
        $fileNames = array(
            '../' . $fileName,
            './../' . $fileName,
            '/../' . $fileName
        );

        foreach ($fileNames as $fileName) {
            $this->_package->cat($fileName);
            $this->assertTrue($this->_result->wasSuccessful());

            $this->_package->head($fileName, 2);
            $this->assertTrue($this->_result->wasSuccessful());

            $this->_package->tail($fileName, 2);
            $this->assertTrue($this->_result->wasSuccessful());
        }
    }
}
