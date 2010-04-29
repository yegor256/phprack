<?php
/**
 * @version $Id: BootstrapTest.php 88 2010-03-17 07:09:16Z yegor256@yahoo.com $
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Runner_AuthResult
 */
require_once PHPRACK_PATH . '/Runner/AuthResult.php';


class AuthResultTest extends AbstractTest
{
    public function testResultInitializeProperly()
    {
        $auth1 = new phpRack_Runner_AuthResult(true);
        $auth2 = new phpRack_Runner_AuthResult(false);
        $this->assertTrue($auth1->isValid(), "AuthResult initializes with wrong values");
        $this->assertFalse($auth2->isValid(), "AuthResult initializes with wrong values");
    }
}
