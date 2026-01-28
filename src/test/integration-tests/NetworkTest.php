<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class NetworkTest extends phpRack_Test
{
    public function testGooglePortsAreOpen()
    {
        $this->assert->network->ports
            ->isOpen(80, 'www.google.com');
    }

    public function testOurIncomingPortIsOpen()
    {
        $this->assert->network->ports
            ->isOpen(80)
            ->isOpen(9999);
    }

    public function testUrlIsAccessible()
    {
        $options = array(
            'connectTimeout' => 5,    // timeouts in seconds
            'readTimeout'    => 60
        );

        // validate that the URL is accessible
        $this->assert->network->url
            ->url('http://www.google.com', $options) // set URL (and validate it here)
            ->regex('/google\.com/') // make HTTP call and find pattern in result
            ->regex('google.com');
    }
}
