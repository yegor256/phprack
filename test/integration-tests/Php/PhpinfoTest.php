<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class Php_PhpinfoTest extends phpRack_Test
{

    public function testPhpinfoIsVisible()
    {
        $this->assert->php->phpinfo();
    }

}