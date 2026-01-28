<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class ParametrizedTest extends PhpRack_Test
{
    protected function _init()
    {
        $this->setAjaxOptions(
            array(
                'tags' => array_keys($this->_getFiles()),
            )
        );
    }
    public function testFiles($tag = null)
    {
        if ($tag) {
            $files = $this->_getFiles();
            $this->assert->disc->file->cat($files[$tag]);
            return;
        }
        $this->_log("Click one of the tags...");
    }
    protected function _getFiles()
    {
        $files = array();
        foreach (glob(PHPRACK_PATH . '/*.php') as $file) {
            $files[pathinfo($file, PATHINFO_FILENAME)] = $file;
        }
        return $files;
    }
}
