<?php
/**
 * @version $Id: CustomTest.php 82 2010-03-16 13:46:41Z yegor256@yahoo.com $
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class ShellTest extends phpRack_Test
{

    public function testWhoAmI()
    {
        $this->assert->shell->exec('who am i');
    }

}