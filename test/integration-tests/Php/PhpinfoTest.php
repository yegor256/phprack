<?php
/**
 * @version $Id: PhpConfigurationTest.php 37 2010-02-27 07:41:05Z yegor256@yahoo.com $
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class PhpinfoTest extends phpRack_Test
{

    public function testPhpinfoIsVisible()
    {
        $this->assert->php->phpinfo();
    }

}