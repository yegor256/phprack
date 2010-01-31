<?php

require_once 'AbstractTest.php';

class ViewTest extends AbstractTest
{
    
    public function testRenderingReturnsValidHtml()
    {
        require_once PHPRACK_PATH . '/Runner.php';
        global $phpRackConfig;
        $runner = new PhpRack_Runner($phpRackConfig);

        require_once PHPRACK_PATH . '/View.php';
        $view = new PhpRack_View($runner);
        
        $html = $view->render();
        $this->assertFalse(empty($html), "Empty HTML, why?");
    }
        
}
