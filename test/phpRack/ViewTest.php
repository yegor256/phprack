<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

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

        $view = new phpRack_View($runner);
        
        $html = $view->render();
        $this->assertFalse(empty($html), "Empty HTML, why?");
    }
        
}
