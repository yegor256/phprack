<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Notifier_Mail
 */
require_once PHPRACK_PATH . '/Adapters/Notifier/Mail.php';

class Adapters_Notifier_MailTest extends AbstractTest
{
    public function testMailFactorySmtp()
    {
        $options = array(
            'class'    => 'smtp',
            'username' => 'john',
            'port'     => 25,
            'host'     => 'smtp.gmail.com',
            'password' => 'dkhfZ34'
        );
        $mail = phpRack_Adapters_Notifier_Mail::factory($options);
        $this->assertTrue($mail instanceof phpRack_Adapters_Notifier_Mail_Smtp);
    }

    public function testMailFactorySendmail()
    {
        $mail = phpRack_Adapters_Notifier_Mail::factory();
        $this->assertTrue($mail instanceof phpRack_Adapters_Notifier_Mail_Sendmail);
    }
}
