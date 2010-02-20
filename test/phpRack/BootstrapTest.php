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

class BootstrapTest extends AbstractTest
{
    
    public function testBootstrapIsRendered()
    {
        global $phpRackConfig;
        include PHPRACK_PATH . '/bootstrap.php';
    }
    
    public function testHttpGetRequestDeliversValidJSON()
    {
        global $phpRackConfig;
        $runner = new phpRack_Runner($phpRackConfig);
        $tests = $runner->getTests();
        $this->assertTrue(count($tests) > 1, 'too few tests, why?');
        
        // get one random test
        shuffle($tests);
        $test = array_shift($tests);
        
        $_GET[PHPRACK_AJAX_TAG] = $test->getFileName();
        $_GET[PHPRACK_AJAX_TOKEN] = 'token';
        
        include PHPRACK_PATH . '/bootstrap.php';
    }
        
}
