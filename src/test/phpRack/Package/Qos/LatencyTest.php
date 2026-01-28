<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

class phpRack_Package_Qos_LatencyTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Qos
     */
    private $_qos;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_qos = $this->_test->assert->qos;
    }

    public function testSingleUrl()
    {
        $this->_qos->latency('http://www.phprack.com');
    }

    public function testMultiUrl()
    {
        $this->_qos->latency(
            array(
                'scenario' => array(
                    'http://www.phprack.com',
                    'http://www.phprack.com/index.html'
                ),
                'averageMs' => 500, // 500ms average per request
                'peakMs' => 2000, // 2s maximum per request
            )
        );
    }

    /**
     */
    public function testWithotUrl(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_qos->latency(
            array(
                'scenario' => array()
            )
        );
    }

    /**
     */
    public function testWithInvalidUrl(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_qos->latency(
            array(
                'scenario' => array('/index.html')
            )
        );
    }

    /**
     */
    public function testWithInvalidOption(): void
    {
        $this->expectException(phpRack_Exception::class);
        $this->_qos->latency(
            array(
                'scenario' => array(
                    'http://www.example.com'
                ),
                'invalidOption' => 500
            )
        );
    }
}
