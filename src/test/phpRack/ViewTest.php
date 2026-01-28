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
 * @see phpRack_Runner
 */
require_once PHPRACK_PATH . '/Runner.php';

/**
 * @see phpRack_View
 */
require_once PHPRACK_PATH . '/View.php';

class ViewTest extends AbstractTest
{

    public function testRenderingReturnsValidHtml()
    {
        global $phpRackConfig;
        $runner = new phpRack_Runner($phpRackConfig);

        $view = new phpRack_View();
        $view->assign(array('runner' => $runner));

        $html = $view->render();
        $this->assertFalse(empty($html), "Empty HTML, why?");
    }

}
