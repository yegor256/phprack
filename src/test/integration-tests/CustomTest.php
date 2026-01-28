<?php
/**
 * AAAAA
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class CustomTest extends phpRack_Test
{

    public function testCustomAssertionsAreValid()
    {
        $this->assert->fail("This test is just failed, always");
    }

}
