<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Mail
 */
require_once PHPRACK_PATH . '/Adapters/Mail.php';

class Adapters_MailTest extends AbstractTest
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testMailFactorySmtp()
    {
        $options = array(
            'smtp' => array(
                'username' => 'john',
                'port' => 25,
                'host' => 'smtp.gmail.com',
                'password' => 'dkhfZ34'),
        );
        $mail = phpRack_Adapters_Mail::factory($options);

        $this->assertTrue($mail instanceof phpRack_Adapters_Mail_Transport_Smtp);
    }

    public function testMailFactorySendmail()
    {
        $a = array();
        $mail = phpRack_Adapters_Mail::factory($a);
        $this->assertTrue($mail instanceof phpRack_Adapters_Mail_Transport_Sendmail);
    }
}
