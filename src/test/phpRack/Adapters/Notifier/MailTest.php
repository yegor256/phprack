<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Notifier_Mail
 */
require_once PHPRACK_PATH . '/Adapters/Notifier/Mail.php';

class Adapters_Notifier_MailTest extends AbstractTest
{
    public function testMailFactorySmtp()
    {
        /**
         * @todo we should setup a real mail account with gmail and use
         *      use it for testing, this account is NOT working
         */
        $mail = phpRack_Adapters_Notifier_Mail::factory(
            'smtp',
            array(
                'username' => 'john',
                'port'     => 25,
                'host'     => 'smtp.gmail.com',
                'password' => 'dkhfZ34'
            )
        );
        $this->assertTrue($mail instanceof phpRack_Adapters_Notifier_Mail_Smtp);
    }

    public function testMailFactorySendmail()
    {
        $mail = phpRack_Adapters_Notifier_Mail::factory();
        $this->assertTrue($mail instanceof phpRack_Adapters_Notifier_Mail_Sendmail);
    }
}
