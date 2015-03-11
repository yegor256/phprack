<?php
/**
 * @version $Id: BootstrapTest.php 88 2010-03-17 07:09:16Z yegor256@yahoo.com $
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Runner_Auth_Result
 */
require_once PHPRACK_PATH . '/Runner/Auth/Result.php';


class AuthResultTest extends AbstractTest
{
    public function testResultInitializeProperly()
    {
        $auth1 = new phpRack_Runner_Auth_Result(true);
        $auth2 = new phpRack_Runner_Auth_Result(false);
        $this->assertTrue($auth1->isValid(), "AuthResult initializes with wrong values");
        $this->assertFalse($auth2->isValid(), "AuthResult initializes with wrong values");
    }
}
