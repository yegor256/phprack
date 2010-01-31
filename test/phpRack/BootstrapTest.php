<?php

require_once 'AbstractTest.php';

class BootstrapTest extends AbstractTest
{
    
    public function testBootstrapIsRendered()
    {
        global $phpRackConfig;
        include PHPRACK_PATH . '/bootstrap.php';
    }
    
    public function testHttpGetRequestDeliversValidJSON()
    {
        require_once PHPRACK_PATH . '/Runner.php';
        global $phpRackConfig;
        $runner = new PhpRack_Runner($phpRackConfig);
        $tests = $runner->getTests();
        $test = array_shift($tests);
        
        $_GET[PHPRACK_AJAX_TAG] = $test->getFileName();
        
        include PHPRACK_PATH . '/bootstrap.php';
    }
        
}
