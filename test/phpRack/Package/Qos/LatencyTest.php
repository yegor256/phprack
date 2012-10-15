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
     * @expectedException phpRack_Exception
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
     * @expectedException phpRack_Exception
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
     * @expectedException phpRack_Exception
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
