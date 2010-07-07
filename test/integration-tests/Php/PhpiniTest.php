<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class PhpiniTest extends phpRack_Test
{
    public function testPhpiniMemoryLimit()
    {
        $this->assert->php->ini('memory_limit')->atLeast('128M');
    }
}
