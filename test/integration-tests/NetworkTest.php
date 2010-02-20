<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class NetworkTest extends PhpRack_Test
{

    public function testGooglePortsAreOpen()
    {
        $this->assert->network->ports
            ->isOpen(80, 'www.google.com');
    }

    public function testOurIncomingPortIsOpen()
    {
        $this->assert->network->ports
            ->isOpen(80);
    }

}