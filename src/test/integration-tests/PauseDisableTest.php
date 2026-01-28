<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class PauseDisableTest extends phpRack_Test
{
    protected function _init()
    {
        $this->setAjaxOptions(
            array(
                'pauseWhenFocusLost' => false, // true by default
            )
        );
    }
    public function testShowLog()
    {
        $fileName = '../test/phpRack/Package/Disc/_files/1000lines.txt';
        $this->assert->disc->file->tailf($fileName);
    }

}
