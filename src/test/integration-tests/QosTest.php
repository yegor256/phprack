<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class QosTest extends PhpRack_Test
{
    public function testLatency()
    {
        $this->assert->qos->latency(
            array(
                'scenario' => array(
                    'http://www.example.com',
                    'http://www.example.com/index.html'
                ),
                'averageMs' => 500, // 500ms average per request
                'peakMs' => 2000, // 2s maximum per request
            )
        );
    }
}
