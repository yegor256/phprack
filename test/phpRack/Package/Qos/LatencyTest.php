<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

class phpRack_Package_Qos_LatencyTest extends AbstractTest
{
    /**
     * @var phpRack_Package_Qos
     */
    private $_qos;

    protected function setUp()
    {
        parent::setUp();
        $this->_qos = $this->_test->assert->qos;
    }

    public function testSingleUrl()
    {
        $this->_qos->latency('http://example.com');
    }

    public function testMultiUrl()
    {
        $this->_qos->latency(
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

    /**
     * @expectedException Exception
     */
    public function testWithotUrl()
    {
        $this->_qos->latency(
            array(
                'scenario' => array()
            )
        );
    }

    /**
     * @expectedException Exception
     */
    public function testWithInvalidUrl()
    {
        $this->_qos->latency(
            array(
                'scenario' => array('/index.html')
            )
        );
    }

    /**
     * @expectedException Exception
     */
    public function testWithInvalidOption()
    {
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
